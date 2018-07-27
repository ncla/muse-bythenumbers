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
        // To future developer when this might become too taxing, maybe switch to old method where votes were given
        // based on least voted matchup, not least voted songs.
        $songVoteCountsSubQuery = 'SELECT `song_votes`.`song_id`, COUNT(*) as song_vote_count
                                    FROM
                                        (SELECT `voting_matchups`.`songA_id` AS song_id
                                        FROM `voting_matchups`
                                        INNER JOIN `votes` ON `voting_matchups`.`id` = `votes`.`voting_matchup_id`
                                        WHERE `voting_matchups`.`voting_ballot_id` = ' . intval($votingBallotID) . '
                                        UNION ALL
                                        SELECT `voting_matchups`.`songB_id` AS song_id
                                        FROM `voting_matchups`
                                        INNER JOIN `votes` ON `voting_matchups`.`id` = `votes`.`voting_matchup_id`
                                        WHERE `voting_matchups`.`voting_ballot_id` = ' . intval($votingBallotID) . ')
                                    song_votes
                                    GROUP BY `song_id`';

        // Query written so less voted songs get priority
        return Matchups::select('voting_matchups.*', 'votesOne.*',
            'song_votes1.song_vote_count as songA_vote_count', 'song_votes2.song_vote_count as songB_vote_count')
            // votesOne is for getting all submitted votes by user_id
            ->leftJoin(DB::raw('(SELECT `user_id`, `voting_matchup_id` FROM `votes` WHERE `user_id` = ' . $userID .') AS votesOne'),
                function($join) {
                    $join->on('votesOne.voting_matchup_id', '=', 'voting_matchups.id');
                })
            ->leftJoin(DB::raw('(' . $songVoteCountsSubQuery . ') as song_votes1'), function($join) {
                $join->on('voting_matchups.songA_id', '=', 'song_votes1.song_id');
            })
            ->leftJoin(DB::raw('(' . $songVoteCountsSubQuery . ') as song_votes2'), function($join) {
                $join->on('voting_matchups.songB_id', '=', 'song_votes2.song_id');
            })
            ->where('voting_matchups.voting_ballot_id', $votingBallotID)
            ->where('votesOne.user_id', null)
            ->where('votesOne.voting_matchup_id', null)
            ->orderBy('songA_vote_count')
            ->orderBy('songB_vote_count')
            ->inRandomOrder()
            ->limit(1)
            ->get()->first();
    }

    public static function getTotalVotes($ballotId, $userID = null)
    {
        $voteCount =  Votes::select(DB::raw('COUNT(*) as count'))
            ->join('voting_matchups', 'votes.voting_matchup_id', '=', 'voting_matchups.id')
            ->where('voting_matchups.voting_ballot_id', $ballotId);

        if ($userID) {
            $voteCount->where('votes.user_id', $userID);
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
            $item->totalVotes = 0;

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

            $allSongsStats[$allSongsStatsKey]->totalVotes = $totalVotes;
        }

        \Debugbar::stopMeasure('allVotes_each2');

        return $allSongsStats;
    }

    public static function getVoteDistribution($votingBallotID)
    {
        return DB::table(with(new Matchups())->getTable())
            ->select('voting_matchups.id', DB::raw('IFNULL(`votesTwo`.`count`, 0) AS `count`'), 'songA.name AS songA_name', 'songB.name AS songB_name',
                'songA.id AS songA_id', 'songB.id AS songB_id')
            ->leftJoin(DB::raw('(SELECT `voting_matchup_id`, COUNT(*) as `count` FROM `votes` GROUP BY `voting_matchup_id`) AS votesTwo'),
                function($join) {
                    $join->on('votesTwo.voting_matchup_id', '=', 'voting_matchups.id');
                })
            ->join('musicbrainz_songs AS songA', 'songA.id', '=', 'voting_matchups.songA_id')
            ->join('musicbrainz_songs AS songB', 'songB.id', '=', 'voting_matchups.songB_id')
            ->where('voting_ballot_id', $votingBallotID)
            ->groupBy('voting_matchups.id')
            ->orderBy('count', 'desc')
            ->get();
    }

    public static function getVoteCountsForAllUsers($votingBallotID)
    {
        return Votes::select('votes.user_id', 'users.username', DB::raw('COUNT(*) as count'))
            ->join('users', 'votes.user_id', '=', 'users.id')
            ->groupBy('user_id')
            ->get();
    }

    public static function getVotingHistory($votingBallotID, $userID = null)
    {
        $votes =  Votes::select(DB::raw('COALESCE(songA.name_override, songA.name) AS songA_name'), DB::raw('COALESCE(songB.name_override, songB.name) AS songB_name'),
            DB::raw('COALESCE(winner_song.name_override, winner_song.name) AS winner_name'),
            'voting_matchups.songA_id', 'voting_matchups.songB_id', 'votes.winner_song_id', 'votes.created_at', 'votes.user_id')
            ->join('voting_matchups', 'votes.voting_matchup_id', '=', 'voting_matchups.id')
            ->join('musicbrainz_songs AS songA', 'songA.id', '=', 'voting_matchups.songA_id')
            ->join('musicbrainz_songs AS songB', 'songB.id', '=', 'voting_matchups.songB_id')
            ->join('musicbrainz_songs AS winner_song', 'winner_song.id', '=', 'votes.winner_song_id')
            ->where('voting_matchups.voting_ballot_id', $votingBallotID);

        if ($userID) {
            $votes->where('votes.user_id', $userID);
        }

        return $votes->get();
    }

}