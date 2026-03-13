<?php

namespace App\Providers;

use App\Listeners\StoreLoginSession;
use App\Listeners\StoreLogoutSession;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            StoreLoginSession::class,
        ],
        Logout::class => [
            StoreLogoutSession::class,
        ],
    ];
}
