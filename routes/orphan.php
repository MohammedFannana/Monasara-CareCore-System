<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrphanController;
use Illuminate\Support\Facades\Route;

Route::prefix('orphan')->group(function () {
    Route::middleware('auth:orphan')->group(function () {
        Route::get('', [OrphanController::class, 'index'])->name('orphan.primary.index');
        Route::get('/balance', [OrphanController::class, 'balance'])->name('orphan.primary.balance');
        Route::get('/message', [MessageController::class, 'create'])->name('orphan.message.create');
        Route::post('amal/message', [MessageController::class, 'amalSendMessage'])->name('orphan.amal.message');
        Route::post('sponsor/message', [MessageController::class, 'SponsorSendMessage'])->name('orphan.sponsor.message');
        Route::get('/notification', [NotificationController::class, 'OrphanNotification'])->name('orphan.notification');
        Route::get('complete-profile/{orphan}', [OrphanController::class, 'completeProfile'])->name('complete.profile');
        Route::put('/complete-profile/{orphan}', [OrphanController::class, 'storeProfile'])->name('complete.profile.store');
    });

    Route::get('/create', [OrphanController::class, 'create'])->name('orphan.create');
    Route::get('/{orphan}/edit', [OrphanController::class, 'edit'])->name('orphan.edit');
    Route::put('/{orphan}', [OrphanController::class, 'update'])->name('orphan.update');
    Route::post('/store', [OrphanController::class, 'store'])->name('orphan.store');
    Route::get('/image/show', [OrphanController::class, 'showImage'])->name('orphan.primary.image');
    Route::get('/video/show', [OrphanController::class, 'showVideo'])->name('orphan.primary.video');
    Route::get('/audio/show', [OrphanController::class, 'showAudio'])->name('orphan.primary.audio');
});
