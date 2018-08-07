<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartHistoryController extends Controller
{
    public function showLastFm()
    {
        $history = DB::table('lastfm_tracks')
            ->select('lastfm_tracks.listeners_week', 'lastfm_tracks.chart_index', DB::raw('ROUND(UNIX_TIMESTAMP(lastfm_tracks.created_at) * 1000) timestamp'),
                DB::raw('COALESCE(musicbrainz_songs.name_lastfm_override, musicbrainz_songs.name_override, musicbrainz_songs.name) as track_name'))
            ->join('musicbrainz_songs', 'lastfm_tracks.track_name', '=',
                DB::raw('COALESCE(musicbrainz_songs.name_lastfm_override, musicbrainz_songs.name_override, musicbrainz_songs.name)'))
            ->whereDate('lastfm_tracks.created_at', '>=', Carbon::now()->subMonth())
            ->orderBy('lastfm_tracks.created_at', 'desc')
            ->get();

        $history = $history->groupBy('track_name');

        $history = $history->map(function ($item, $key) {
            return [
                'name' => $key,
                'data' => $item->map(function($it, $ke) {
                    return [$it->timestamp, $it->chart_index];
                })
            ];
        })->values();

        return view('charts.lastfm')
            ->with('chart', $history);
    }
}
