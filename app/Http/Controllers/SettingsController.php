<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateSettings as UpdateSettingsRequest;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{

    public function index(Request $request)
    {
        return view('members/my');
    }

    public function update(UpdateSettingsRequest $request)
    {
        settings()->merge($request->get('settings'));

        Flash::success('Settings have been saved!');

        return redirect()->action('SettingsController@index');
    }

}
