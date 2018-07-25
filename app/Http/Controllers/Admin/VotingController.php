<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateVotingRequest;
use App\Http\Requests\UpdateVotingRequest;
use App\Models\Voting;
use App\Repositories\VotingRepository;
use App\Http\Controllers\AppBaseController;
use App\Songs;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class VotingController extends AppBaseController
{
    /** @var  VotingRepository */
    private $votingRepository;

    public function __construct(VotingRepository $votingRepo)
    {
        $this->votingRepository = $votingRepo;
    }

    /**
     * Display a listing of the Voting.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->votingRepository->pushCriteria(new RequestCriteria($request));
        $votings = $this->votingRepository->all();

        return view('admin.votings.index')
            ->with('votings', $votings);
    }

    /**
     * Show the form for creating a new Voting.
     *
     * @return Response
     */
    public function create()
    {
        $songsAvailable = Songs::where('is_utilized', 1)->get();

        return view('admin.votings.create')
            ->with('songsAvailable', $songsAvailable)
            ->with('songsSelected', collect());
    }

    /**
     * Store a newly created Voting in storage.
     *
     * @param CreateVotingRequest $request
     *
     * @return Response
     */
    public function store(CreateVotingRequest $request)
    {
        $input = $request->all();

        $voting = $this->votingRepository->create($input);

        DB::statement('INSERT INTO `voting_matchups` (`songA_id`, `songB_id`, `voting_ballot_id`)
                SELECT `a`.`song_id` AS `songA_id`, `b`.`song_id` AS `songB_id`, ? AS `voting_ballot_id` 
                FROM `voting_ballot_songs` AS `a`, `voting_ballot_songs` AS `b` 
                WHERE `a`.`voting_ballot_id` = ? AND `b`.`voting_ballot_id` = ? 
                AND `a`.`song_id` != `b`.`song_id` AND `a`.`song_id` < `b`.`song_id`', [$voting->id, $voting->id, $voting->id]);

        Flash::success('Voting created successfully.');

        return redirect(route('votings.index'));
    }

    /**
     * Display the specified Voting.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $voting = $this->votingRepository->findWithoutFail($id);

        if (empty($voting)) {
            Flash::error('Voting not found');

            return redirect(route('votings.index'));
        }

        $songs = DB::table('voting_ballot_songs')
                    ->select(['voting_ballot_songs.song_id', 'musicbrainz_songs.name'])
                    ->where('voting_ballot_id', $id)
                    ->join('musicbrainz_songs', 'musicbrainz_songs.id', 'voting_ballot_songs.song_id')
                    ->get();

        $matchups = DB::table('voting_matchups')
                            ->select(['a_song.name AS songA_name', 'b_song.name AS songB_name'])
                            ->join('musicbrainz_songs AS a_song', 'voting_matchups.songA_id', 'a_song.id')
                            ->join('musicbrainz_songs AS b_song', 'voting_matchups.songB_id', 'b_song.id')
                            ->where('voting_matchups.voting_ballot_id', $id)
                            ->get();

        return view('admin.votings.show')
            ->with('voting', $voting)
            ->with('songs', $songs)
            ->with('matchups', $matchups);
    }

    public function showStats($id)
    {
        $voting = $this->votingRepository->findWithoutFail($id);

        if (empty($voting)) {
            Flash::error('Voting not found');

            return redirect(route('votings.index'));
        }

        $voteDistribution = \App\Services\Voting::getVoteDistribution($id);

        $ranks = \App\Services\Voting::calculateStatsFromVotes($id);

        $ranks = $ranks->sortByDesc('winrate');

        $userVotes = \App\Services\Voting::getVoteCountsForAllUsers($id)->sortByDesc('count');

        return view('admin.votings.stats')
            ->with('matchups', $voteDistribution)
            ->with('votes_user', $userVotes)
            ->with('ranks', $ranks);
    }

    /**
     * Show the form for editing the specified Voting.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $voting = $this->votingRepository->findWithoutFail($id);

        if (empty($voting)) {
            Flash::error('Voting not found');

            return redirect(route('votings.index'));
        }

        $songsSelected = DB::table('voting_ballot_songs')
                                ->select('*')
                                ->join('musicbrainz_songs', 'voting_ballot_songs.song_id', 'musicbrainz_songs.id')
                                ->where('voting_ballot_id', $id)
                                ->get();

        $songsSelectedArray = $songsSelected->map(function ($item, $key) {
            return $item->song_id;
        })->toArray();

        //dump($songsSelectedArray);

        $songsAvailable = DB::table('musicbrainz_songs')
                                ->whereNotIn('id', $songsSelectedArray)
                                ->get();

        return view('admin.votings.edit')
            ->with('voting', $voting)
            ->with('songsAvailable', $songsAvailable)
            ->with('songsSelected', $songsSelected);
    }

    /**
     * Update the specified Voting in storage.
     *
     * @param  int              $id
     * @param UpdateVotingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVotingRequest $request)
    {
        $voting = $this->votingRepository->findWithoutFail($id);

        if (empty($voting)) {
            Flash::error('Voting not found');

            return redirect(route('votings.index'));
        }

//        dump($request->all());

        $voting = $this->votingRepository->update($request->all(), $id);

        // Determine what missing songs there are now, and delete them
        // Determine what new songs are added, and add them
        // Clean up song list and match up list

        $newSongList = collect($request->all()['songs']);

        $oldSongList = DB::table('voting_ballot_songs')
                            ->select('song_id')
                            ->where('voting_ballot_id', $id)
                            ->get();

        $oldSongList = $oldSongList->map(function ($item, $key) {
            return $item->song_id;
        });


        $newSongList = $newSongList->map(function ($item, $key) {
            return $item['song_id'];
        });

//        dump('new song list', $newSongList, 'oldsonglist', $oldSongList);

        $songAdditions = $newSongList
            ->diff($oldSongList);

        $songRemovals = $oldSongList->diff($newSongList);

//        dump($songAdditions, $songRemovals);

        DB::table('voting_ballot_songs')
            ->where('voting_ballot_id', $id)
            ->whereIn('song_id', $songRemovals->toArray())
            ->delete();

        DB::table('voting_matchups')
            ->where('voting_ballot_id', $id)
            ->whereIn('songA_id', $songRemovals->toArray())
            ->orWhereIn('songB_id', $songRemovals->toArray())
            ->delete();

        DB::table('voting_ballot_songs')
            ->insert(
                $songAdditions->map(function ($item) use($id) {
                    return ['song_id' => $item, 'voting_ballot_id' => $id];
                })->toArray()
            );

        // Do the same recalculation of matchups with same query, and insert ignore on duplicate is probably easiest way
        DB::statement('INSERT IGNORE INTO `voting_matchups` (`songA_id`, `songB_id`, `voting_ballot_id`)
                SELECT `a`.`song_id` AS `songA_id`, `b`.`song_id` AS `songB_id`, ? AS `voting_ballot_id`
                FROM `voting_ballot_songs` AS `a`, `voting_ballot_songs` AS `b`
                WHERE `a`.`voting_ballot_id` = ? AND `b`.`voting_ballot_id` = ?
                AND `a`.`song_id` != `b`.`song_id` AND `a`.`song_id` < `b`.`song_id`', [$voting->id, $voting->id, $voting->id]);

        //dd($songAdditions, $songRemovals);

        Flash::success('Voting updated successfully.');

        return redirect(route('votings.show', ['id' => $id]));
    }

    /**
     * Remove the specified Voting from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $voting = $this->votingRepository->findWithoutFail($id);

        if (empty($voting)) {
            Flash::error('Voting not found');

            return redirect(route('votings.index'));
        }

        $this->votingRepository->delete($id);

        Flash::success('Voting deleted successfully.');

        return redirect(route('votings.index'));
    }
}
