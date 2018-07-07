<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVote;
use App\Services\Songs as SongsService;
use Illuminate\Http\Request;
use App\Models\Voting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Debugbar;
use App\Services\Voting as VotingService;
use Carbon\Carbon;

class VotingController extends Controller
{
    public function __construct()
    {
        $this->votingService = new VotingService();
        $this->songService = new SongsService();
    }

    public function index()
    {
        $votings = Voting::with('songs')->get();

        //dump($votings);

        return view('voting.index')
            ->with('votings', $votings);
    }

    public function show($id)
    {
        //dump($id);

        $ballot = Voting::findOrFail($id);

        return view('voting.vote')
            ->with('ballot', $ballot);
    }

    public function vote($id, CreateVote $request)
    {
        $ballot = Voting::findOrFail($id);

        if (!$ballot->open_status) {
            abort(403, 'Voting ballot is closed and you can no longer vote on it.');
        }

        if ($request->input('voted_on') !== null) {

            Voting\Votes::create([
                'user_id' => Auth::user()->id,
                'voting_matchup_id' => $request->input('voting_matchup_id'),
                'winner_song_id' => $request->input('voted_on')
            ]);

        }

        $matchup = $this->votingService->getMatchUp($ballot->id, Auth::user()->id);

        $totalMatchUps = DB::table('voting_matchups')
                            ->select(DB::raw('COUNT(*) AS count'))
                            ->where('voting_ballot_id', $id)
                            ->get()->first()->count;

        $totalCompletedByUser = DB::table('votes')
            ->select(DB::raw('COUNT(*) AS count'))
            ->join('voting_matchups', 'voting_matchups.id', 'votes.voting_matchup_id')
            ->where('voting_matchups.voting_ballot_id', $id)
            ->where('votes.user_id', Auth::user()->id)
            ->get()->first()->count;

        $songA = $songB = null;

        if ($matchup !== null) {
            $songA = $this->songService->getSong($matchup->songA_id);

            $songB = $this->songService->getSong($matchup->songB_id);
        }

        return response()->json([
            'ballot' => $ballot,
            'matchup' => [
                'matchup_data' => $matchup,
                'songs' => [
                    $songA, $songB
                ]
            ],
            'user_voting_progress' => [
                'votes_submitted' => $totalCompletedByUser,
                'votes_total' => $totalMatchUps
            ]
        ]);
    }
}
