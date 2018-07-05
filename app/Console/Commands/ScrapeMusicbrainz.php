<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scrapers\MusicBrainz\Songs;

class ScrapeMusicbrainz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:musicbrainz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fuck = new Songs($this);
        $fuck->artistId = env('MUSICBRAINZ_MBID_ARTIST');
        $fuck->start();
    }
}
