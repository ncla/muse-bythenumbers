<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Statistics@show');

Route::get('/debug', 'Statistics@debug');

Route::get('/members/login', 'Auth\LoginController@redirectToProvider')->name('login');

Route::get('/members/login/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/members/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/voting-ballots/', 'VotingController@index');
Route::get('/voting-ballots/{id}', 'VotingController@show');
Route::post('/voting-ballots/{id}/vote', 'VotingController@vote')->middleware('auth');
Route::get('/voting-ballots/{id}/vote-debug', 'VotingController@vote')->middleware('auth');
Route::get('/voting-ballots/{id}/my/stats', 'VotingController@mystats')->middleware('auth');
Route::get('/voting-ballots/{id}/my/history', 'VotingController@myhistory')->middleware('auth');

Route::get('songs/{id}', 'SongsController@show');

Route::get('chart-history/lastfm', 'ChartHistoryController@showLastFm');
Route::get('chart-history/spotify', 'ChartHistoryController@showSpotifyTop10');

Route::get('about', function() {
    return view('about');
});

Route::group(['middleware' => 'can:manage-voting-ballots'], function() {
    Route::resource('admin/votings', 'Admin\VotingController');

    Route::get('admin/votings/{id}/stats', 'Admin\VotingController@showStats');
});

Route::group(['middleware' => 'can:manage-users'], function() {
    Route::resource('admin/users', 'Admin\UserController');
});

Route::group(['middleware' => 'can:manage-songs'], function() {
    Route::get('admin/songs/bulk', 'Admin\SongsController@bulkIndex')->name('admin.songs.bulk');
    Route::patch('admin/songs/bulk', 'Admin\SongsController@bulkPatch')->name('admin.songs.bulk.patch');

    Route::get('admin/songs/bulkmissing', 'Admin\SongsController@bulkShowMissingTracks')->name('admin.songs.bulkmissing');
    Route::patch('admin/songs/bulkmissing', 'Admin\SongsController@bulkPatchMissingTracks')->name('admin.songs.bulkmissing.patch');

    Route::resource('admin/songs', 'Admin\SongsController');
});

Route::resource('setlists', 'SetlistController');