<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSetlistRequest;
use App\Http\Requests\UpdateSetlistRequest;
use App\Repositories\SetlistRepository;
use App\Http\Controllers\AppBaseController;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SetlistController extends AppBaseController
{
    use SEOTools;

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

        $this->seo()->setTitle('Setlists');
        $this->seo()->setDescription('All setlists');

        return view('setlists.index')
            ->with('setlists', $setlists)
            ->with('searchValues', $searchQueryWithKeys);
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

        if (empty($setlist)) {
            Flash::error('Setlist not found');

            return redirect(route('setlists.index'));
        }

        $this->seo()->setTitle($setlist->venueFullName . ' - Setlist');
        $this->seo()->setDescription('Setlist for ' . $setlist->venueFullName);

        return view('setlists.show')
            ->with('setlist', $setlist);
    }

}
