<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Flag event checkouts that are past their expected return, and tell the branch.
Schedule::command('inventory:mark-overdue')->dailyAt('07:00');
