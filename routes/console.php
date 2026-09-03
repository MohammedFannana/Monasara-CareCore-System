<?php

use App\Console\Commands\UpdateSponsorshipStatus;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(UpdateSponsorshipStatus::class)->everyFiveMinutes();
Schedule::command('model:prune')->monthly('03:00');


