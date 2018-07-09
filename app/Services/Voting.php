<?php

namespace App\Services;

use App\Models\Voting\Matchups;
use App\Models\Voting\Songs;
use App\Models\Voting\Votes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Src\Rating;

class Voting
{

    public static function getMatchUp($votingBallotID, $userID)
    {
        // Query written so less voted matchups get priority
        return Matchups::select('voting_matchups.*', 'votesOne.*', 'votesTwo.*', DB::raw('IFNULL(`votesTwo`.`count`, 0) AS `vote_count`'))
            // votesOne is for getting all submitted votes by user_id
            ->leftJoin(DB::raw('(SELECT `user_id`, `voting_matchup_id` FROM `votes` WHERE `user_id` = ' . $userID .') AS votesOne'),
                function($join) {
                    $join->on('votesOne.voting_matchup_id', '=', 'voting_matchups.id');
                })
            // votesTwo is used to get COUNT for voting matchups for determining which matchup needs a vote (so all matchups are voted evenly)
            ->leftJoin(DB::raw('(SELECT `voting_matchup_id`, COUNT(*) as `count` FROM `votes` GROUP BY `voting_matchup_id`) AS votesTwo'),
                function($join) {
                    $join->on('votesTwo.voting_matchup_id', '=', 'voting_matchups.id');
                })
            ->where('voting_matchups.voting_ballot_id', $votingBallotID)
            ->where('votesOne.user_id', null)
            ->where('votesOne.voting_matchup_id', null)
            ->orderBy('vote_count')
            ->inRandomOrder()
            ->limit(1)
            ->get()->first();
    }

    public static function getTotalVotes($ballotId, $userId = null)
    {
        $voteCount =  Votes::select(DB::raw('COUNT(*) as count'))
            ->join('voting_matchups', 'votes.voting_matchup_id', '=', 'voting_matchups.id')
            ->where('voting_matchups.voting_ballot_id', $ballotId);

        if ($userId) {
            $voteCount->where('votes.user_id', $userId);
        }

        return $voteCount->get()->first();
    }

    public static function calculateStatsFromVotes($votingBallotID, $userID = null)
    {
        \Debugbar::startMeasure('allvotes_query');
        // voting matchups > votes
        $allVotes = DB::table(with(new Matchups())->getTable())
                ->join('votes', 'votes.voting_matchup_id', '=', 'voting_matchups.id')
                ->where('voting_ballot_id', $votingBallotID)
                ->orderBy('votes.created_at', 'asc');

        if ($userID) {
            $allVotes->where('votes.user_id', $userID);
        }

        $allVotes = $allVotes->get();

        \Debugbar::stopMeasure('allvotes_query');

        //dump($allVotes); return;

        // all songs have ID, iterate through all vote results, calcualte winrate and

        // all songs to DB voting_ballot_songs

        \Debugbar::startMeasure('allsongs_query');

        // TODO: Index?
        $allSongs = DB::table(with(new Songs())->getTable())
                        ->select('song_id', DB::raw('COALESCE(musicbrainz_songs.name_override, musicbrainz_songs.name) as name'))
                        ->join('musicbrainz_songs', 'musicbrainz_songs.id', '=', 'voting_ballot_songs.song_id')
                        ->where('voting_ballot_id', $votingBallotID)
                        ->get();

        \Debugbar::stopMeasure('allsongs_query');
        \Debugbar::startMeasure('allsongstats_map');

        $allSongsStats = $allSongs->map(function($item) {
            $item->won = 0;
            $item->lost = 0;
            $item->rank = 1000;

            return $item;
        });

        $allSongsStats = $allSongsStats->keyBy('song_id');

        \Debugbar::stopMeasure('allsongstats_map');

        \Debugbar::startMeasure('allVotes_each1');

        $allVotes->each(function($item) use (&$allSongsStats) {
            // Checking if song IDs exist in $allSongsStats. This can happen if voting ballot has swapped some songs.
            if (!isset($allSongsStats[$item->songA_id], $allSongsStats[$item->songB_id])) {
                return false;
            }

            $loserId = ($item->songA_id === $item->winner_song_id) ? $item->songB_id : $item->songA_id;
            $winnerId = $item->winner_song_id;

            // TODO: If we are weighing users with many votes in, then we have to pull user total votes here
            $allSongsStats[$winnerId]->won++;
            $allSongsStats[$loserId]->lost++;

            $winnerTotalMatches = $allSongsStats[$winnerId]->won + $allSongsStats[$winnerId]->lost;
            $loserTotalMatches = $allSongsStats[$loserId]->won + $allSongsStats[$loserId]->lost;

            // For ELO rating
            //dd($allSongsStats[$winnerId]->rank, $allSongsStats[$loserId]->rank, Rating::WIN, Rating::LOST);
            $match = new Rating($allSongsStats[$winnerId]->rank, $allSongsStats[$loserId]->rank, Rating::WIN, Rating::LOST);
            $result = $match->getNewRatings();

            //dd($result);
            $allSongsStats[$winnerId]->rank = $result['a'];
            $allSongsStats[$loserId]->rank = $result['b'];

        });

        \Debugbar::stopMeasure('allVotes_each1');

        \Debugbar::startMeasure('allVotes_each2');

        foreach ($allSongsStats as $allSongsStatsKey => $allSongsStatsVal) {
            $totalVotes = $allSongsStats[$allSongsStatsKey]->won + $allSongsStats[$allSongsStatsKey]->lost;

            // Division by zero handling
            if ($allSongsStats[$allSongsStatsKey]->won === 0 || $totalVotes === 0) {
                $allSongsStats[$allSongsStatsKey]->winrate = 0;
            } else {
                $allSongsStats[$allSongsStatsKey]->winrate = ($allSongsStats[$allSongsStatsKey]->won / $totalVotes) * 100;
            }
        }

        \Debugbar::stopMeasure('allVotes_each2');

        return $allSongsStats;
    }

}