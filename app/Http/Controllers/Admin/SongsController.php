<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateSongsRequest;
use App\Http\Requests\UpdateSongsRequest;
use App\Repositories\SongsRepository;
use App\Http\Controllers\AppBaseController;
use App\Songs;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SongsController extends AppBaseController
{
    /** @var  SongsRepository */
    private $songsRepository;

    public function __construct(SongsRepository $songsRepo)
    {
        $this->songsRepository = $songsRepo;
    }

    /**
     * Display a listing of the Songs.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->songsRepository->pushCriteria(new RequestCriteria($request));
        $songs = $this->songsRepository->all();

        return view('admin.songs.index')
            ->with('songs', $songs);
    }

    /**
     * Show the form for creating a new Songs.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.songs.create');
    }

    /**
     * Store a newly created Songs in storage.
     *
     * @param CreateSongsRequest $request
     *
     * @return Response
     */
    public function store(CreateSongsRequest $request)
    {
        $input = $request->all();

        $input['is_utilized'] = ($input['is_utilized'] === '1') ? true : false;
        $input['manually_added'] = ($input['manually_added'] === '1') ? true : false;

        $songs = $this->songsRepository->create($input);

        Flash::success('Songs saved successfully.');

        return redirect(route('songs.index'));
    }

    /**
     * Display the specified Songs.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $songs = $this->songsRepository->findWithoutFail($id);

        if (empty($songs)) {
            Flash::error('Songs not found');

            return redirect(route('songs.index'));
        }

        return view('admin.songs.show')->with('songs', $songs);
    }

    /**
     * Show the form for editing the specified Songs.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $songs = $this->songsRepository->findWithoutFail($id);

        if (empty($songs)) {
            Flash::error('Songs not found');

            return redirect(route('songs.index'));
        }

        return view('admin.songs.edit')->with('songs', $songs);
    }

    /**
     * Update the specified Songs in storage.
     *
     * @param  int              $id
     * @param UpdateSongsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSongsRequest $request)
    {
        $songs = $this->songsRepository->findWithoutFail($id);

        if (empty($songs)) {
            Flash::error('Songs not found');

            return redirect(route('songs.index'));
        }

        $songs = $this->songsRepository->update($request->all(), $id);

        Flash::success('Songs updated successfully.');

        return redirect(route('songs.show', ['id' => $id]));
    }

    /**
     * Remove the specified Songs from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $songs = $this->songsRepository->findWithoutFail($id);

        if (empty($songs)) {
            Flash::error('Songs not found');

            return redirect(route('songs.index'));
        }

        $this->songsRepository->delete($id);

        Flash::success('Songs deleted successfully.');

        return redirect(route('songs.index'));
    }

    public function bulkIndex(Request $request)
    {
        $this->songsRepository->pushCriteria(new RequestCriteria($request));
        $songs = $this->songsRepository->all();

        return view('admin.songs.bulk')
            ->with('songs', $songs);
    }

    public function bulkPatch(Request $request)
    {
        if (empty($request->input('selected'))) {
            Flash::error('No songs were selected.');
        }

        // For most part we lossly assume things here because admin panel.
        Songs::whereIn('id', $request->input('selected'))
            ->update([
                $request->input('action') => $request->input('boolean')
            ]);


        Flash::success('Song statuses were changed successfully.');

        return redirect(route('admin.songs.bulk'));
    }

    public function bulkShowMissingTracks(Request $request)
    {
        $songs = [];

        $songsSetlist = DB::table('setlist_songs')
            ->select('name')
            ->groupBy('name')
            ->whereNotIn('name', function($query)
            {
                $query->select(DB::raw('musicbrainz_songs.name'))
                    ->from('musicbrainz_songs')
                    ->whereRaw('musicbrainz_songs.name = setlist_songs.name');
            })
            ->get()->pluck('name');

        $songsSpotify = DB::table('spotify_tracks')
            ->select('track_name as name')
            ->groupBy('track_name')
            ->whereNotIn('track_name', function($query)
            {
                $query->select(DB::raw('musicbrainz_songs.name'))
                    ->from('musicbrainz_songs')
                    ->whereRaw('musicbrainz_songs.name = spotify_tracks.track_name');
            })
            ->get()->pluck('name');

        $merged = $songsSetlist->merge($songsSpotify)->unique()->sort()->values()->toArray();

        //dump($songs, $songsSetlist, $songsSpotify, $merged);

        return view('admin.songs.bulkmissing')
            ->with('songs', $merged);
    }

    public function bulkPatchMissingTracks(Request $request)
    {
        foreach($request->input('selected') as $name) {
            $song = Songs::firstOrCreate(['name' => $name]);
            $song->manually_added = false;
            $song->is_utilized = false;
            $song->save();
        }

        Flash::success('Success.');

        return redirect(route('admin.songs.bulkmissing'));
    }

}
