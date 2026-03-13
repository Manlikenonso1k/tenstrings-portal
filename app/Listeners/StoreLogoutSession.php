<?php

namespace App\Listeners;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Auth\Events\Logout;

class StoreLogoutSession
{
    public function handle(Logout $event): void
    {
        $user = $event->user;

        if (! $user instanceof User) {
            return;
        }

        LoginSession::query()
            ->where('user_id', $user->id)
            ->whereNull('logout_at')
            ->latest('login_at')
            ->limit(1)
            ->update(['logout_at' => now()]);
    }
}
