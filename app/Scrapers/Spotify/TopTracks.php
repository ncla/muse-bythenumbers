<?php

namespace App\Scrapers\Spotify;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use App\Models\Spotify\TopTrack;
use Carbon\Carbon;

class TopTracks
{
    public $SpotifyArtistId = null;

    public $marketCode = 'GB';

    protected $responses = [];

    protected $tracksDB = [];

    public function __construct($console)
    {
        if (env('SPOTIFY_CLIENT_ID') === null) {
            throw new ErrorException('Spotify SPOTIFY_CLIENT_ID is not set in ENV file!');
        }

        if (env('SPOTIFY_CLIENT_SECRET') === null) {
            throw new ErrorException('Spotify SPOTIFY_CLIENT_SECRET is not set in ENV file!');
        }

        $this->console = $console;

        return $this;
    }

    public function start()
    {
        $this->scrapeTopChart()->saveIntoDb();

        return $this;
    }

    public function scrapeTopChart()
    {
        if ($this->SpotifyArtistId === null) {
            throw new ErrorException('Spotify Artist ID is not set!');
        }

        $spotifySession = new Session(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'));
        $spotifySession->requestCredentialsToken();
        $spotifyAccessToken = $spotifySession->getAccessToken();

        $spotifyApi = new SpotifyWebAPI();
        $spotifyApi->setAccessToken($spotifyAccessToken);

        $top = $spotifyApi->getArtistTopTracks($this->SpotifyArtistId, ['country' => $this->marketCode]);

        $currentTime = Carbon::now();

        foreach ($top->tracks as $chartIndex => $track) {
            $this->tracksDB[] = [
                'track_id' => $track->id,
                'chart_index' => $chartIndex + 1,
                'created_at' => $currentTime,
                'updated_at' => $currentTime
            ];
        }

        return $this;
    }

    public function saveIntoDb()
    {
        TopTrack::insertOnDuplicateKey($this->tracksDB);

        return $this;
    }

}