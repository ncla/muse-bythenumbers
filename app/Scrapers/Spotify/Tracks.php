<?php

namespace App\Scrapers\Spotify;

use App\Models\Spotify\Album;
use App\Models\Spotify\Track;
use App\Setlist;
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
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class Tracks
{
    public $SpotifyArtistId = null;

    protected $responses = [];

    protected $albumsDB = [];

    protected $tracksDB = [];

    public function __construct(\Illuminate\Console\Command $console)
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
        $this->scrapeAllTracks()->parseApiResponses()->saveIntoDb();

        return $this;
    }

    public function scrapeAllTracks()
    {
        if ($this->SpotifyArtistId === null) {
            throw new ErrorException('Spotify Artist ID is not set!');
        }

        $spotifySession = new Session(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'));
        $spotifySession->requestCredentialsToken();
        $spotifyAccessToken = $spotifySession->getAccessToken();

        $spotifyApi = new SpotifyWebAPI();
        $spotifyApi->setAccessToken($spotifyAccessToken);

        $albumList = collect();

        $offset = 0;

        while($offset >= 0) {
            $albums = $spotifyApi->getArtistAlbums(
                $this->SpotifyArtistId,
                [
                    'album_type' => ['album', 'single'],
                    'limit' => 50,
                    'offset' => $offset
                ]
            );

            $albumList = $albumList->merge($albums->items);

            $offset = ($albums->next === null) ? -1 : $offset + 50;
        }

        $albumList = $albumList->keyBy('id');

        $albumIDs = $albumList->map(function ($item, $key) {
            return $item->id;
        });

        $albumIDChunks = $albumIDs->chunk(20);

        $albumIDChunks->each(function ($chunk, $chunkIndex) use($spotifyApi, $albumList) {
            $albumIdsImploded = $chunk->implode(',');

            $albumsListAPI = collect($spotifyApi->getAlbums($albumIdsImploded)->albums);

            $albumsListAPI->each(function ($albumAPI, $albumAPIIndex) use($albumList) {
                $albumList[$albumAPI->id]->album_resource = $albumAPI;
            });
        });

        $this->responses = $albumList;

        return $this;
    }

    public function parseApiResponses()
    {
        $this->responses->each(function ($album, $albumKey) {
            $this->albumsDB[] = [
                'album_id' => $album->id,
                'album_name' => $album->name,
                'release_date' => $album->release_date,
                'album_type' => $album->album_type,
                'image_url' => $album->images[0]->url,
                'image_width' => $album->images[0]->width,
                'image_height' => $album->images[0]->height
            ];

            foreach($album->album_resource->tracks->items as $track) {
                $this->tracksDB[] = [
                    'track_id' => $track->id,
                    'track_name' => $track->name,
                    'track_number' => $track->track_number,
                    'album_id' => $album->id,
                    'duration_ms' => $track->duration_ms,
                    'preview_url_mp3' => $track->preview_url
                ];
            }
        });

        return $this;
    }

    public function saveIntoDb()
    {
        Album::insertOnDuplicateKey($this->albumsDB);

        $chunkedTracks = array_chunk($this->tracksDB, 1000);

        foreach ($chunkedTracks as $chunkDb) {
            Track::insertOnDuplicateKey($chunkDb);
        }

        return $this;
    }
}