<?php

namespace App\Http\Controllers;

use App\Setlist;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Statistics extends Controller
{
    public function show()
    {
        $years = DB::table('setlists')
            ->select([DB::raw('DATE_FORMAT(date, "%Y") as year')])
            ->orderBy('year', 'DESC')
            ->groupBy(['year'])
            ->get();

        $works = DB::table('musicbrainz_songs')
            ->select([DB::raw('COALESCE(musicbrainz_songs.name_override, musicbrainz_songs.name) as name_final'), 'musicbrainz_songs.mbid',
                'musicbrainz_songs.name_override', 'musicbrainz_songs.id', 'performances.*', 'lastfm_tracks.listeners_week'])
            ->leftJoin(DB::raw('
                (SELECT `lastfm_tracks`.`track_name`, `lastfm_tracks`.`listeners_week`, `lastfm_tracks`.`created_at`
                FROM `lastfm_tracks`
                INNER JOIN (
                    SELECT `track_name`, MAX(`created_at`) created_at_newest FROM `lastfm_tracks` 
                    GROUP BY `track_name`
                ) aLastFm ON `lastfm_tracks`.`track_name` = `aLastFm`.`track_name` AND `lastfm_tracks`.`created_at` = `aLastFm`.`created_at_newest`
                ORDER BY `listeners_week` desc) as lastfm_tracks
           '), 'lastfm_tracks.track_name', '=',
                    DB::raw('COALESCE(musicbrainz_songs.name_lastfm_override, musicbrainz_songs.name_override, musicbrainz_songs.name)'))
            ->leftJoin(DB::raw('(' . DB::table('setlist_songs')
                ->select('setlist_songs.name', 'setlists.date', DB::raw('COUNT(*) as playcount'), DB::raw('MAX(setlists.date) as last_played'))
                ->addSelect($years->map(function ($year) {
                    return DB::raw('IFNULL(SUM(CASE WHEN YEAR(setlists.date) = ' . $year->year . ' THEN 1 ELSE 0 END), 0) AS total_' . $year->year);
                })->toArray())
                ->join('setlists', 'setlist_songs.id', 'setlists.id')
                ->groupBy(['setlist_songs.name'])
                ->toSql() . ') AS performances'), 'performances.name', '=',
                    DB::raw('COALESCE(musicbrainz_songs.name_setlistfm_override, musicbrainz_songs.name_override, musicbrainz_songs.name)'))
            ->where('musicbrainz_songs.is_utilized', '=', '1')
            ->orderBy('musicbrainz_songs.name')
            ->get();

        //dump($works);

        return view('statistics', [
            'works' => $works,
            'years_columns' => $years
        ]);
    }

    public function debug()
    {
        // 1. is musicbrainz works all
        // 2. musicbrainz works that couldnt be found in setlist (by name)
        // 3. setlist songs that couldnt be found in musicbrainz works
        $works = DB::table('musicbrainz_songs')
            ->select(['musicbrainz_songs.*', 'setlist_songs.name as setlist_name'])
            ->leftJoin('setlist_songs', 'musicbrainz_songs.name', 'setlist_songs.name')
//            ->join('setlists', 'setlist_songs.id', 'setlists.id')
            ->groupBy('musicbrainz_songs.name')
            ->orderBy('musicbrainz_songs.name')
            ->get();

        $works2 = DB::table('musicbrainz_songs')
            ->select(['musicbrainz_songs.*', 'setlist_songs.name as setlist_name'])
            ->leftJoin('setlist_songs', 'musicbrainz_songs.name', 'setlist_songs.name')
//            ->join('setlists', 'setlist_songs.id', 'setlists.id')
            ->groupBy('musicbrainz_songs.name')
            ->orderBy('musicbrainz_songs.name')
            ->where('setlist_songs.name', '=', null)
            ->get();

        $works3 = DB::table('setlist_songs')
            ->select(['setlist_songs.*', 'musicbrainz_songs.name as musicbrainz_name'])
            ->leftJoin('musicbrainz_songs', 'setlist_songs.name', 'musicbrainz_songs.name')
            ->groupBy('setlist_songs.name')
            ->orderBy('setlist_songs.name')
            ->where('musicbrainz_songs.name', '=', null)
            ->get();


        //dump($works3);

        return view('debug', [
            'debug1' => $works,
            'debug2' => $works2,
            'debug3' => $works3
        ]);
    }
}
