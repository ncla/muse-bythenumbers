<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSetlistRequest;
use App\Http\Requests\UpdateSetlistRequest;
use App\Repositories\SetlistRepository;
use App\Http\Controllers\AppBaseController;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Scrapers\SetlistFm\Main as Scraper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
     * @param  string $id
     *
     * @return Response
     */
    public function show($id)
    {
        $setlist = $this->setlistRepository->findWithoutFailWithTrashed($id);

        if (empty($setlist)) {
            Flash::error('Setlist not found');

            return redirect(route('setlists.index'));
        }

        $this->seo()->setTitle($setlist->venueFullName . ' - Setlist');
        $this->seo()->setDescription('Setlist for ' . $setlist->venueFullName);

        return view('setlists.show')
            ->with('setlist', $setlist);
    }

    /**
     * Refresh particular Setlist from API
     *
     * @param string $id
     *
     * @return Redirect
     */
    public function refresh($id)
    {
        $setlist = $this->setlistRepository->findWithoutFail($id);

        try {
            Artisan::call('scrape:setlistfm', [
                '--id' => [$id]
            ]);

            Log::debug(Artisan::output());

            Flash::success("Set-list for \"" . $setlist->venueFullName . "\" has been updated!");

        } catch(\Exception $exception) {

            report($exception);
            Flash::error('Error updating set-list');

        }

        return redirect()->back();
    }

    /**
     * Soft-delete a set-list. (Soft) deleted set-lists will be ignored from statistics, hidden on site.
     *
     * @param string $id
     *
     * @throws \Exception
     *
     * @return Redirect
     */
    public function delete($id)
    {
        $setlist = $this->setlistRepository->findWithoutFail($id);

        if (!$setlist->trashed()) {
            $deletionResult = $setlist->delete();

            if ($deletionResult === true) {
                Flash::success("Set-list \"" . $setlist->venueFullName . "\" has been soft-deleted!");
            } else {
                Flash::warning("Set-list soft-deletion for \"" . $setlist->venueFullName . "\" failed!");
            }
        } else {
            Flash::warning("Set-list \"" . $setlist->venueFullName . "\" is already soft-deleted!");
        }

        return redirect()->back();
    }

    /**
     * Restore soft-deleted set-list.
     *
     * @param string $id
     *
     * @throws \Exception
     *
     * @return Redirect
     */
    public function restore($id)
    {
        $setlist = $this->setlistRepository->findWithoutFailWithTrashed($id);

        if ($setlist->trashed()) {
            $restoreResult = $setlist->restore();

            if ($restoreResult === true) {
                Flash::success("Set-list \"" . $setlist->venueFullName . "\" has been restored!");
            } else {
                Flash::warning("Set-list restoration for \"" . $setlist->venueFullName . "\" failed!");
            }
        } else {
            Flash::warning("Set-list \"" . $setlist->venueFullName . "\" is already restored!");
        }

        return redirect()->back();
    }

    /**
     * Toggle is_utilized status. Set-lists that are not utilized will not be used in calculations, but they can be searched on the site.
     *
     * @param string $id
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return Redirect
     */
    public function utilize($id, Request $request)
    {
        $setlist = $this->setlistRepository->findWithoutFailWithTrashed($id);

        $setlist->is_utilized = $request->get('set') == 1;
        $saveResult = $setlist->save();

        if ($saveResult === true) {
            Flash::success("Set-list \"" . $setlist->venueFullName . "\" has been marked with is_utilized = " . var_export($setlist->is_utilized, true));
        } else {
            Flash::warning("Changing utilization status for \"" . $setlist->venueFullName . "\" failed!");
        }

        return redirect()->back();
    }


}
