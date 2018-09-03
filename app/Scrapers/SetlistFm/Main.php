<?php

namespace App\Scrapers\SetlistFm;

use App\Models\Setlist;
use GuzzleHttp;
use GuzzleHttp\Promise\EachPromise;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use ErrorException;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ResponseInterface;

class Main
{
    public $artistId = null;

    protected $responses = [];

    protected $setlistsDB = [];

    protected $songsDB = [];

    public function __construct(\Illuminate\Console\Command $console)
    {
        if (env('SETLISTFM_KEY') === null) {
            throw new ErrorException('Setlist.fm API developer key is not set in ENV file!');
        }

        $this->console = $console;

        $this->client = new \GuzzleHttp\Client(['headers' => [
            'accept' => 'application/json',
            'x-api-key' => env('SETLISTFM_KEY')
        ],
            'on_stats' => function (GuzzleHttp\TransferStats $stats) {
                $this->console->info("URL: {$stats->getEffectiveUri()}");
                $this->console->info("Completed in: {$stats->getTransferTime()}s | Status: {$stats->getResponse()->getStatusCode()}");

                // You must check if a response was received before using the
                // response object.
                if (!$stats->hasResponse()) {
                    $this->console->error($stats->getHandlerErrorData());
                }
            }
        ]);

        return $this;
    }

    protected function getRequestPoolConfig()
    {
        return [
            'concurrency' => 3,
            'fulfilled' => function (\Psr\Http\Message\ResponseInterface $response, $index) {
                $this->responses[] = $response;
            },
            'rejected' => function (\GuzzleHttp\Exception\RequestException $reason, $index) {
                $this->console->error($reason->getMessage());
            },
        ];
    }

    public function start($setlistIds = [])
    {
        if (empty($setlistIds)) {
            $this->scrapeAllSetlists($this->artistId);

            foreach($this->responses as $response) {
                $this->parseApiResponseSetlistList($response);
            }
        } else {
            $this->scrapeIndividualSetlists($setlistIds);

            foreach($this->responses as $response) {
                $this->parseApiResponseSetlistItem($response);
            }
        }

        $this->saveIntoDb();

        return $this;
    }

    public function scrapeIndividualSetlists($setlistIds = [])
    {
        if (empty($setlistIds)) {
            throw new ErrorException('No setlist IDs provided!');
        }

        foreach($setlistIds as $setlistId) {
            $response = $this->client->request('GET', "https://api.setlist.fm/rest/1.0/setlist/{$setlistId}");
            $this->responses[] = $response;
        }
    }

    public function scrapeAllSetlists($artistId = null)
    {
        if ($artistId === null) {
            throw new ErrorException('MUSICBRAINZ Artist ID is not set!');
        }

        $res = $this->client->request('GET', "https://api.setlist.fm/rest/1.0/artist/{$this->artistId}/setlists");

        $decoded = json_decode($res->getBody());

        $pageCount = ceil($decoded->total / $decoded->itemsPerPage);

        $this->responses[] = $res;

        $i = 2;

        $requests = (function () use ($pageCount, $i) {
            while ($i <= $pageCount) {
                yield new Request('GET', "https://api.setlist.fm/rest/1.0/artist/{$this->artistId}/setlists?p={$i}");
                $i++;
            }
        })();

        $pool = new Pool($this->client, $requests, $this->getRequestPoolConfig());

        $promise = $pool->promise();
        $promise->wait();

        $this->console->info('Completed requesting all pages of setlists');

        return $this;
    }

    public function parseApiResponseSetlistList(\Psr\Http\Message\ResponseInterface $response)
    {
        $response = json_decode($response->getBody());

        foreach($response->setlist as $setlistKey => $setlistItem) {
            $this->parseSetlistItemInSetlistArray($setlistItem);
        }

        return $this;
    }

    public function parseApiResponseSetlistItem(\Psr\Http\Message\ResponseInterface $response)
    {
        $response = json_decode($response->getBody());

        $this->parseSetlistItemInSetlistArray($response);

        return $this;
    }

    public function parseSetlistItemInSetlistArray($setlistItem)
    {
        $this->setlistsDB[] = [
            'id' => $setlistItem->id,
            'date' => Carbon::parse($setlistItem->eventDate)->toDateString(),
            'venue' => json_encode($setlistItem->venue),
            'url' => $setlistItem->url
        ];

        $songIndexOverall = 0;

        foreach($setlistItem->sets->set as $set) {
            foreach($set->song as $songIndex => $song) {
                // There are some edge cases where there is no name, such as for Unknown songs.
                if($song->name === '') {
                    continue;
                }

                $this->songsDB[] = [
                    'id' => $setlistItem->id,
                    'name' => $song->name,
                    'tape' => property_exists($song, 'tape') ? true : false,
                    'encore' => property_exists($set, 'encore') ? $set->encore : 0,
                    'note' => property_exists($song, 'info') ? $song->info : null,
                    'order_nr_in_set' => $songIndex,
                    'order_nr_overall' => $songIndexOverall
                ];

                $songIndexOverall++;
            }
        }
    }

    public function saveIntoDb()
    {
        Setlist::insertOnDuplicateKey($this->setlistsDB);

        // DB inserts should be chunked, otherwise we hit query insert limit: 1390 Prepared statement contains too many placeholders
        // Additionally since corrections to set may be made, we need to clear whole set and insert again.
        // Updating it won't work since the song might change position and so on..

        // Manipulate with Illuminate\Collection because lazy
        $collection = collect($this->songsDB);

        $groupedCollection = $collection->groupBy('id');

        $groupedCollection->each(function(Collection $setSongsDB, $setlistId) {
            DB::table('setlist_songs')
                ->where('id', '=', $setlistId)
                ->delete();

            DB::table('setlist_songs')->insert($setSongsDB->toArray());
        });

//        $splitSongListForDb = array_chunk($this->songsDB, 1000);
//
//        foreach ($splitSongListForDb as $chunkedSongsDb) {
//            DB::table('setlist_songs')->insert($chunkedSongsDb);
//        }

        return $this;
    }
}