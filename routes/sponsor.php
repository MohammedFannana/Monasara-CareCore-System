<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Sponsor\MessageController as SponsorMessageController;
use App\Http\Controllers\Sponsor\SponsorController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sponsor')->prefix('sponsor')->name('sponsor.')->group(function () {
    Route::get('waiting/orphan', [SponsorController::class, 'waitingIndex'])->name('orphan.waiting.index');
    Route::get('sponsorship/orphan', [SponsorController::class, 'sponsorIndex'])->name('orphan.sponsor.index');
    Route::get('/orphan/{orphan}/media', [SponsorController::class, 'media'])->name('orphan.media');
    Route::get('waiting/orphan/view/{orphan}', [SponsorController::class, 'waitingView'])->name('orphan.waiting.view');
    Route::get('sponsor/orphan/view/{orphan}', [SponsorController::class, 'sponsorView'])->name('orphan.sponsor.view');
    Route::get('sponsorship/orphan/view/{orphan}', [SponsorController::class, 'sponsorView'])->name('orphan.sponsorship.view');
    Route::get('sponsorship/orphan/create', [SponsorController::class, 'create'])->name('orphan.create');
    Route::post('sponsorship/orphan/store', [SponsorController::class, 'store'])->name('orphan.store');
    Route::get('message', [SponsorMessageController::class, 'view'])->name('message.view');
    Route::get('/notification', [NotificationController::class, 'SponsorNotification'])->name('notification');
});
