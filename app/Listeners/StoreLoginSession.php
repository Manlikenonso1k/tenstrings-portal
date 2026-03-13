<?php

namespace App\Listeners;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Auth\Events\Login;

class StoreLoginSession
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (! $user instanceof User) {
            return;
        }

        LoginSession::query()->create([
            'user_id' => $user->id,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'login_at' => now(),
        ]);
    }
}
