<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Support\Facades\Request;
use App\Models\Users\Logins;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     *
     * @param  LoginEvent  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        Logins::firstOrCreate(
            ['user_id' => $event->user->id, 'ip_address' => Request::getClientIp()],
            ['browser_useragent' => Request::userAgent()]
        );
    }
}
