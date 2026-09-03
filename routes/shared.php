<?php

use App\Http\Controllers\Association\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sponsor\SponsorController;
use Illuminate\Support\Facades\Route;

Route::get('/review/{orphan}', [ReviewController::class, 'create'])->name('orphan.review');

Route::middleware('auth:sponsor,association')->group(function () {
    Route::get('sponsorship/show/{orphan}', [SponsorController::class, 'sponsorshipView'])->name('sponsorship.show');
    Route::get('gift/show/{orphan}', [SponsorController::class, 'giftView'])->name('gift.show');
    Route::get('sponsorship/orphan/payments/{orphan}', [SponsorController::class, 'orphanPayments'])->name('orphan.payments');
});

Route::middleware('auth:sponsor,association,researcher,web,orphan')->group(function () {
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::post('/payment/session', [PaymentController::class, 'createSession'])->name('payment.session');
    Route::get('/payment/response', [PaymentController::class, 'paymentResponse'])->name('payment.response');
    Route::post('/payment/temp-store', [PaymentController::class, 'tempStore'])->name('payment.temp.store');
});
