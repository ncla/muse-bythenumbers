<?php

namespace App\Console\Commands;

use App\Scrapers\Spotify\Tracks;
use Illuminate\Console\Command;

class ScrapeSpotifyTracks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:spotifytracks';

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
        $fuck = new Tracks($this);
        $fuck->SpotifyArtistId = env('SPOTIFY_ARTIST_ID');
        $fuck->start();
    }
}
