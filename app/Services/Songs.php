<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Songs
{
    public function getSong($id)
    {
        return DB::table('musicbrainz_songs')
            ->select('musicbrainz_songs.id', 'musicbrainz_songs.name', 'spotify_tracks.track_id AS spotify_track_id', 'spotify_tracks.track_name AS spotify_track_name',
                'spotify_tracks.album_id AS spotify_album_id', 'spotify_tracks.duration_ms', 'spotify_tracks.preview_url_mp3')
            ->leftJoin('spotify_tracks', function ($join) {
                $join->on('spotify_tracks.track_name', 'LIKE',
                    DB::raw("CONCAT('%', TRIM(COALESCE(musicbrainz_songs.name_spotify_override, musicbrainz_songs.name_override, musicbrainz_songs.name)), '%')"));
            })
            ->where('musicbrainz_songs.id', $id)
            ->orderBy(DB::raw('(`spotify_tracks`.`track_name` = COALESCE(musicbrainz_songs.name_spotify_override, musicbrainz_songs.name_override, musicbrainz_songs.name))'), 'desc')
            ->orderBy(DB::raw('length(`spotify_tracks`.`track_name`)'))
            ->get()->first();
    }
}