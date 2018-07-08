<?php

namespace App\Services;

use App\Models\Voting\Matchups;
use App\Models\Voting\Songs;
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

    public static function calculateStatsFromVotes($votingBallotID)
    {
        // voting matchups > votes
        $allVotes = DB::table(with(new Matchups())->getTable())
                ->join('votes', 'votes.voting_matchup_id', '=', 'voting_matchups.id')
                ->where('voting_ballot_id', $votingBallotID)
                ->orderBy('votes.created_at')
                ->get();

        // all songs have ID, iterate through all vote results, calcualte winrate and

        // all songs to DB voting_ballot_songs

        $allSongs = DB::table(with(new Songs())->getTable())
                        ->where('voting_ballot_id', $votingBallotID)
                        ->get();


        $allSongsStats = $allSongs->map(function($item) {
            $obj = new \stdClass();

            $obj->song_id = $item->song_id;
            $obj->won = 0;
            $obj->lost = 0;
            $obj->rank = 1000;

            return $obj;
        });

        $allSongsStats = $allSongsStats->keyBy('song_id');

        $allVotes->each(function($item) use (&$allSongsStats) {
            // TODO: Check if song ids exist in $allSongsStats
            $loserId = ($item->songA_id === $item->winner_song_id) ? $item->songB_id : $item->songA_id;
            $winnerId = $item->winner_song_id;
            // For winrate
            // TODO: If we are weighing users with many votes in, then we have to pull user total votes here
            $allSongsStats[$winnerId]->won = $allSongsStats[$winnerId]->won + 1;
            $allSongsStats[$loserId]->lost = $allSongsStats[$loserId]->lost + 1;

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

        foreach ($allSongsStats as $allSongsStatsKey => $allSongsStatsVal) {
            $totalVotes = $allSongsStats[$allSongsStatsKey]->won + $allSongsStats[$allSongsStatsKey]->lost;
            $allSongsStats[$allSongsStatsKey]->winrate = ($allSongsStats[$allSongsStatsKey]->won / $totalVotes) * 100;
        }

        return $allSongsStats;
    }

}