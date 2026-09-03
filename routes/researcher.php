<?php

use App\Http\Controllers\Association\ExpenseController;
use App\Http\Controllers\Association\OrphanController as AssociationOrphanController;
use App\Http\Controllers\Association\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrphanController;
use App\Http\Controllers\OrphanMediaController;
use App\Http\Controllers\Researcher\ResearcherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:researcher')->prefix('researcher')->name('researcher.')->group(function () {
    Route::get('/orphan', [ResearcherController::class, 'index'])->name('orphan.index');
    Route::get('/registered', [ResearcherController::class, 'registeredOrphan'])->name('registered');

    Route::get('/orphans/create', [AssociationOrphanController::class, 'create'])->name('orphans.create');
    Route::get('/first/orphans/create', [ResearcherController::class, 'create'])->name('orphans.first.create');
    Route::post('/first/orphans', [ResearcherController::class, 'store'])->name('orphans.first.store');

    Route::get('/orphans/{orphan}/edit', [OrphanController::class, 'edit'])->name('orphans.edit');
    Route::get('view/orphan/{orphan}', [ResearcherController::class, 'view'])->name('orphan.view');
    Route::post('/researcher/review', [ReviewController::class, 'researcherReview'])->name('orphan.review');

    Route::get('/orphans/media', [OrphanMediaController::class, 'index'])->name('orphan.media.index');
    Route::post('/orphans/media', [OrphanMediaController::class, 'store'])->name('orphan.media.store');

    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');

    Route::get('/message', [MessageController::class, 'ResearcherViewMessage'])->name('message.view');
    Route::get('/notification', [NotificationController::class, 'ResearcherNotification'])->name('notification');
});
