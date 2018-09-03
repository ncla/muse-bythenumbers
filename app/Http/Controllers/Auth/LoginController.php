<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        if(!session()->has('from')) {
            session()->put('from', url()->previous());
        }

        return Socialite::with('reddit')
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $user = $this->findOrCreateRedditUser(
            Socialite::driver('reddit')->user()
        );

        Auth::login($user);

        return redirect(session()->pull('from', $this->redirectTo));
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    protected function findOrCreateRedditUser($redditUser)
    {
        $user = User::firstOrNew(['username' => $redditUser->nickname]);

        if($user->exists) return $user;

        $user->fill([
            'username' => $redditUser->nickname,
            'avatar' => $redditUser->user['icon_img']
        ])->save();

        return $user;
    }
}
