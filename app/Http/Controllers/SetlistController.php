<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSetlistRequest;
use App\Http\Requests\UpdateSetlistRequest;
use App\Repositories\SetlistRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SetlistController extends AppBaseController
{
    /** @var  SetlistRepository */
    private $setlistRepository;

    public function __construct(SetlistRepository $setlistRepo)
    {
        $this->setlistRepository = $setlistRepo;
    }

    /**
     * Display a listing of the Setlist.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->setlistRepository->with('songs')->pushCriteria(new RequestCriteria($request));
        $setlists = $this->setlistRepository->orderBy('date', 'desc')->paginate(10);

        $searchQuery = $request->get('search');

        $searchQueryWithKeys = [];

        if ($searchQuery) {
            $searchQueryArr = explode(';', $searchQuery);
            $searchQueryWithKeys = [];

            foreach ($searchQueryArr as $query) {
                $explode = explode(':', $query);
                $searchQueryWithKeys[$explode[0]] = $explode[1];
            }
        }

        return view('setlists.index')
            ->with('setlists', $setlists)
            ->with('searchValues', $searchQueryWithKeys);
    }

    /**
     * Show the form for creating a new Setlist.
     *
     * @return Response
     */
    public function create()
    {
        return view('setlists.create');
    }

    /**
     * Store a newly created Setlist in storage.
     *
     * @param CreateSetlistRequest $request
     *
     * @return Response
     */
    public function store(CreateSetlistRequest $request)
    {
        $input = $request->all();

        $setlist = $this->setlistRepository->create($input);

        Flash::success('Setlist saved successfully.');

        return redirect(route('setlists.index'));
    }

    /**
     * Display the specified Setlist.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $setlist = $this->setlistRepository->findWithoutFail($id);

        //dump($setlist, $setlist->songs()->get()->toArray());

        if (empty($setlist)) {
            Flash::error('Setlist not found');

            return redirect(route('setlists.index'));
        }

        return view('setlists.show')->with('setlist', $setlist);
    }

    /**
     * Show the form for editing the specified Setlist.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $setlist = $this->setlistRepository->findWithoutFail($id);

        if (empty($setlist)) {
            Flash::error('Setlist not found');

            return redirect(route('setlists.index'));
        }

        return view('setlists.edit')->with('setlist', $setlist);
    }

    /**
     * Update the specified Setlist in storage.
     *
     * @param  int              $id
     * @param UpdateSetlistRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSetlistRequest $request)
    {
        $setlist = $this->setlistRepository->findWithoutFail($id);

        if (empty($setlist)) {
            Flash::error('Setlist not found');

            return redirect(route('setlists.index'));
        }

        $setlist = $this->setlistRepository->update($request->all(), $id);

        Flash::success('Setlist updated successfully.');

        return redirect(route('setlists.index'));
    }

    /**
     * Remove the specified Setlist from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $setlist = $this->setlistRepository->findWithoutFail($id);

        if (empty($setlist)) {
            Flash::error('Setlist not found');

            return redirect(route('setlists.index'));
        }

        $this->setlistRepository->delete($id);

        Flash::success('Setlist deleted successfully.');

        return redirect(route('setlists.index'));
    }
}
