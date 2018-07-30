<?php

namespace App\Console\Commands;

use App\Scrapers\Spotify\TopTracks;
use Illuminate\Console\Command;

class ScrapeSpotifyTopTracks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:spotifytop';

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
        $fuck = new TopTracks($this);
        $fuck->SpotifyArtistId = env('SPOTIFY_ARTIST_ID');
        $fuck->start();
    }
}
