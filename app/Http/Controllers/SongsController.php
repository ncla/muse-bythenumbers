<?php

namespace App\Http\Controllers;

use App\Models\Setlist;
use App\Models\SetlistSong;
use App\Models\Songs;
use App\Models\Voting\Results;
use App\Services\Voting;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SongsController extends Controller
{
    use SEOTools;

    public function show($id)
    {
        $song = Songs::findOrFail($id);

        // TODO: Find ways to rewrite all this in Eloquent, maybe.. depending on performance.
        $setlistApperances = DB::table('setlist_songs')
                                ->select('setlist_songs.name', DB::raw('COUNT(*) as playcount'), DB::raw('YEAR(setlists.date) as year'))
                                ->where('name', $song->setlistName)
                                ->join('setlists', 'setlists.id', '=', 'setlist_songs.id')
                                ->groupBy(DB::raw('YEAR(setlists.date)'))
                                ->orderBy('setlists.date')
                                ->where('setlists.is_utilized', '=', 1)
                                ->whereNull('setlists.deleted_at')
                                ->get();

        $setlistApperancesYears = $setlistApperances->pluck('year');
        $setlistApperancesPlays = $setlistApperances->pluck('playcount');

        $totalGigsPerYear = DB::table('setlists')
                                    ->select([DB::raw('YEAR(setlists.date) as year'), DB::raw('COUNT(*) as total')])
                                    ->groupBy(DB::raw('YEAR(setlists.date)'))
                                    ->orderBy('setlists.date')
                                    ->where('is_utilized', '=', 1)
                                    ->whereNull('setlists.deleted_at')
                                    ->get();

        $totalGigsPerYear = $totalGigsPerYear->keyBy('year');

        $totalGigsInYear = $setlistApperances->map(function($val) use ($totalGigsPerYear) {
            return $totalGigsPerYear[$val->year]->total;
        });

        $lookAroundSongsSetlist = DB::table('setlist_songs as A')
                                    ->select('A.id', 'A.name', 'setlists.is_utilized AS setlist_is_utilized',
                                        DB::raw('(SELECT name FROM `setlist_songs` B
                                                   WHERE B.order_nr_overall = A.order_nr_overall - 1
                                                   AND A.id = B.id
                                                   LIMIT 1) as previous'),
                                        DB::raw('(SELECT name FROM `setlist_songs` C
                                                   WHERE C.order_nr_overall = A.order_nr_overall + 1
                                                   AND A.id = C.id
                                                   LIMIT 1) as next')
                                    )
                                    ->join('setlists', 'A.id', '=', 'setlists.id')
                                    ->where('A.name', $song->setlistName)
                                    ->where('setlists.is_utilized', '=', 1)
                                    ->whereNull('setlists.deleted_at')
                                    ->get();

        $previousOnly = $lookAroundSongsSetlist->groupBy('previous')->sortByDesc(function ($entries) {
            return count($entries);
        });

        $nextOnly = $lookAroundSongsSetlist->groupBy('next')->sortByDesc(function ($entries) {
            return count($entries);
        });

        $votingResultId = Voting::getMostRecentPublicVotingBallotResultId();

        if ($votingResultId) {
            $votingResultModel = Results::with('songResults', 'songResults.song')->find($votingResultId);

            if ($votingResultModel) {
                $songsAroundByEloRank = Voting::getSongsAroundIndexInSongResults($votingResultModel, $id, 'elo_rank', 4);
                $songsAroundByWinrate = Voting::getSongsAroundIndexInSongResults($votingResultModel, $id, 'winrate', 4);
                $individualSong = $votingResultModel->songResults->where('song_id', $id)->first();
            }
        }

        $lastFmListenersHistory = DB::table('lastfm_tracks')
                                            ->select('*')
                                            ->orderBy('created_at')
                                            ->where('track_name', $song->lastfmName)
                                            ->get();

        $spotifyAlbumArtUrl = new \App\Services\Songs();
        $spotifyAlbumArtUrl = $spotifyAlbumArtUrl->getSong($id)->album_image_url;

        $this->seo()->setTitle($song->name);

        if ($spotifyAlbumArtUrl) {
            $this->seo()->addImages($spotifyAlbumArtUrl);
        }

        return view('songs.show')
            ->with('song', $song)
            ->with('albumImageUrl', $spotifyAlbumArtUrl)
            ->with('statsSetlistAppearance', [
                'years' => $setlistApperancesYears,
                'plays' => $setlistApperancesPlays,
                'totalgigs' => $totalGigsInYear
            ])
            ->with('setlistPrevNextTrack', [
                'prev' => $previousOnly,
                'next' => $nextOnly
            ])
            ->with('lastfmListenerHistory', [
                'listeners' => $lastFmListenersHistory->map(function($item) {
                    return [strtotime($item->created_at) * 1000, $item->listeners_week];
                }),
                'chart_index' => $lastFmListenersHistory->map(function($item) {
                    return [strtotime($item->created_at) * 1000, $item->chart_index];
                })
            ])
            ->with('songsAroundByElo', $songsAroundByEloRank)
            ->with('songsAroundByWinrate', $songsAroundByWinrate)
            ->with('votingIndividualSongResult', $individualSong);
    }
}
