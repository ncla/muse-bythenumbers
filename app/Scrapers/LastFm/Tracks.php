<?php

namespace App\Scrapers\LastFm;

use GuzzleHttp;
use GuzzleHttp\Promise\EachPromise;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Models\LastFm\Track;
use Carbon\Carbon;

class Tracks {
    protected $responses = [];

    protected $tracksDB = [];

    public function __construct(\Illuminate\Console\Command $console)
    {
        if (env('MUSICBRAINZ_MBID_ARTIST') === null) {
            throw new ErrorException('Musicbrainz Artist ID is not set in ENV file!');
        }

        $this->console = $console;

        $this->client = new \GuzzleHttp\Client([
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

    public function start()
    {
        $this->scrapeAllTracks()->parseResponses()->saveIntoDb();

        return $this;
    }

    public function scrapeAllTracks()
    {
        $resp = $this->client->get('http://musicbrainz.org/ws/2/artist/' . env('MUSICBRAINZ_MBID_ARTIST') . '?inc=url-rels&fmt=json');

        $decoded = json_decode($resp->getBody());

        $lastFmBaseUrl = null;

        if (isset($decoded->relations)) {
            foreach ($decoded->relations as $relation) {
                if ($relation->type === 'last.fm') {
                    $lastFmBaseUrl = $relation->url->resource;
                    break;
                }
            }
        }

        if ($lastFmBaseUrl === null) {
            throw new \ErrorException('Last.fm URL not found');
        }

        $artistUrlMatch = preg_match("/last.fm\/music\/(.*)(?=\/|$)/i", $lastFmBaseUrl, $matches);

        if (!isset($matches[1])) {
            throw new \ErrorException('Unexpected artist URL path.');
        }

        // TODO: Not all artists will have 10 pages
        for ($i = 1; $i <= 10; $i++) {
            $respLoop = $this->client->get('https://www.last.fm/music/' . $matches[1] . '/+tracks?date_preset=LAST_7_DAYS&page=' . $i);
            $this->responses[] = (string) $respLoop->getBody();
        }

        //dd((string) $t->getBody());

        return $this;
    }

    public function parseResponses()
    {
        libxml_use_internal_errors(true);

        $currentTime = Carbon::now();

        foreach($this->responses as $response) {
            $dom = new \DOMDocument();
            $dom->loadHTML(mb_convert_encoding($response, 'HTML-ENTITIES', 'UTF-8'));

            $xpath = new \DOMXPath($dom);
            $table = $xpath->query('//table[contains(@class, \'chartlist\')]');

            if (!isset($table[0])) {
                continue;
            }

            $rows = $xpath->query('tbody/tr', $table[0]);

            foreach ($rows as $row) {
                $name = $xpath->query('td[contains(@class, \'chartlist-name\')]/span/a', $row);
                $listeners = $xpath->query('td[contains(@class, \'chartlist-countbar\')]//span[@class="countbar-bar-value"]//text()[1]', $row);
                $chartIndex = $xpath->query('td[@class="chartlist-index"]', $row);

                // Possibly ads, those don't have the elements we need
                if (!isset($name[0], $listeners[0], $chartIndex[0])) {
                    continue;
                }

                $this->tracksDB[] = [
                    'chart_index' => intval(trim($chartIndex[0]->nodeValue)),
                    'track_name' => trim($name[0]->nodeValue),
                    'listeners_week' => intval(str_replace(',', '', trim($listeners[0]->nodeValue))),
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime
                ];
            }

            dump($this->tracksDB);

        }

        return $this;
    }

    public function saveIntoDb()
    {

        $chunkedTracks = array_chunk($this->tracksDB, 500);

        foreach ($chunkedTracks as $chunkDb) {
            Track::insertOnDuplicateKey($chunkDb, ['chart_index', 'listeners_week', 'updated_at']);
        }

        return $this;
    }
}