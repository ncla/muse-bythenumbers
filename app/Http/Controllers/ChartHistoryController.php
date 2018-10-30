<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartHistoryController extends Controller
{
    use SEOTools;

    public function showLastFm()
    {
        $history = DB::table('lastfm_tracks')
            ->select('lastfm_tracks.listeners_week', 'lastfm_tracks.chart_index', DB::raw('ROUND(UNIX_TIMESTAMP(lastfm_tracks.created_at) * 1000) timestamp'),
                DB::raw('COALESCE(musicbrainz_songs.name_lastfm_override, musicbrainz_songs.name_override, musicbrainz_songs.name) as track_name'))
            ->join('musicbrainz_songs', 'lastfm_tracks.track_name', '=',
                DB::raw('COALESCE(musicbrainz_songs.name_lastfm_override, musicbrainz_songs.name_override, musicbrainz_songs.name)'))
            ->whereDate('lastfm_tracks.created_at', '>=', Carbon::now()->subMonth(3))
            ->where('musicbrainz_songs.is_utilized', 1)
            ->where('lastfm_tracks.chart_index', '<=', 50)
            ->orderBy('lastfm_tracks.created_at', 'desc')
            ->get();

        $timestampsUnique = $history->pluck('timestamp')->unique()->flip();

        $timestampsUnique = $timestampsUnique->map(function($val, $index) {
            return [$index, null];
        });

        $history = $history->groupBy('track_name')->sortKeys();

        $history = $history->map(function ($item, $key) use($timestampsUnique) {
            $timeline = clone $timestampsUnique;

            $item->each(function($it, $ke) use($timeline) {
                if (isset($timeline[$it->timestamp])) {
                    $timeline[$it->timestamp] = [
                        'x' => $it->timestamp,
                        'y' => $it->chart_index,
                        'listeners_7day' => $it->listeners_week
                    ];
                }
            });

            $timeline = $timeline->values();

            return [
                'name' => $key,
                'data' => $timeline
            ];
        })->values();

        $this->seo()->setTitle('LastFM 7 Day Chart Index History');

        return view('charts.lastfm')
            ->with('chart', $history);
    }

    public function showSpotifyTop10()
    {
        $history = DB::table('spotify_top_tracks')
            ->select('spotify_top_tracks.chart_index', DB::raw('ROUND(UNIX_TIMESTAMP(spotify_top_tracks.created_at) * 1000) timestamp'),
                DB::raw('COALESCE(musicbrainz_songs.name_spotify_override, musicbrainz_songs.name_override, musicbrainz_songs.name) as track_name'))
            ->join('spotify_tracks', 'spotify_tracks.track_id', '=', 'spotify_top_tracks.track_id')
            ->join('musicbrainz_songs', 'spotify_tracks.track_name', '=',
                DB::raw('COALESCE(musicbrainz_songs.name_spotify_override, musicbrainz_songs.name_override, musicbrainz_songs.name)'))
            ->whereDate('spotify_top_tracks.created_at', '>=', Carbon::now()->subMonth(3))
            ->where('musicbrainz_songs.is_utilized', 1)
            ->orderBy('spotify_top_tracks.created_at', 'asc')
            ->get();

        // Each song shared the same timeline of timestamps. If a song doesn't have a recorded chart index at certain point,
        // then that point is marked as null. This is so that the chart doesn't connect points that have a gap in time.
        // That's why we are manipulating data in such way for the chart

        $timestampsUnique = $history->pluck('timestamp')->unique()->flip();

        $timestampsUnique = $timestampsUnique->map(function($val, $index) {
            return [$index, null];
        });

        $history = $history->groupBy('track_name')->sortKeys();

        $history = $history->map(function (Collection $item, $key) use($timestampsUnique) {
            $timeline = clone $timestampsUnique;

            $item->each(function($it, $ke) use($timeline) {
                if (isset($timeline[$it->timestamp])) {
                    $timeline[$it->timestamp] = [$it->timestamp, $it->chart_index];
                }
            });

            $timeline = $timeline->values();

            return [
                'name' => $key,
                'data' => $timeline
            ];
        })->values();

        $this->seo()->setTitle('Spotify TOP10 Chart Index History');

        return view('charts.spotify')
            ->with('chart', $history);
    }
}
