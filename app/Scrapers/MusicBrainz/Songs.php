<?php

namespace App\Scrapers\MusicBrainz;

use GuzzleHttp\TransferStats;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class Songs
{
    public $artistId = null;

    protected $responses = [];

    protected $songsDB = [];

    public function __construct(\Illuminate\Console\Command $console)
    {
        $this->console = $console;

        return $this;
    }

    public function start() {
        $this->scrape($this->artistId);

        foreach($this->responses as $response) {
            $this->parseApiResponse($response);
        }

        $this->saveIntoDb();

        return $this;
    }

    public function scrape($artistId = null)
    {
        if ($artistId === null) {
            throw new ErrorException('MUSICBRAINZ Artist ID is not set!');
        }

        $client = new Client(['headers' => [
            'accept' => 'application/json',
        ],
            'on_stats' => function (TransferStats $stats) {
                $this->console->info("URL: {$stats->getEffectiveUri()}");
                $this->console->info("Completed in: {$stats->getTransferTime()}s | Status: {$stats->getResponse()->getStatusCode()}");

                // You must check if a response was received before using the
                // response object.
                if (!$stats->hasResponse()) {
                    $this->console->error($stats->getHandlerErrorData());
                }
            }
        ]);

        $i = 0;
        $last = false;

        while ($last === false) {
            $offset = $i * 100;
            $res = $client->request('GET', "http://musicbrainz.org/ws/2/work?artist={$artistId}&limit=100&offset={$offset}");

            $parsed = json_decode($res->getBody());

            $this->responses[] = $parsed;

            $workCount = $parsed->{'work-count'};
            $workOffset = $parsed->{'work-offset'};

            if (($workCount - $workOffset) < 100) {
                $last = true;
            } else {
                sleep(1);
            }

            $i++;
        }

        return $this;
    }

    public function parseApiResponse($response)
    {
        foreach($response->works as $work) {
            $this->songsDB[] = [
                'name' => $work->title,
                'mbid' => $work->id
            ];
        }

        return $this;
    }

    public function saveIntoDb()
    {
        DB::table('musicbrainz_songs')->insert($this->songsDB);
    }

}