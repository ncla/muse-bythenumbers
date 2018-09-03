<?php

use Illuminate\Http\Request;
use App\Models\Setlist;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::get('/setlists', function (Request $request) {
//    return App\Setlist::orderBy('date', 'desc')
//        ->limit(10)
//        ->get();
//});
//
//Route::get('/setlist/{id}', function (Request $request, $id) {
//    //DB::enableQueryLog();
//    $test = App\Setlist::find($id);
//    $test->songs;
//
//    return $test;
//
//    //dd(DB::getQueryLog());
//
//});
//
//Route::get('/songs', function (Request $request) {
//    return App\Setlist::orderBy('date', 'desc')
//        ->limit(10)
//        ->get();
//});