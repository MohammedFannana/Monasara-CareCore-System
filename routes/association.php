<?php

use App\Http\Controllers\Association\AssociationController;
use App\Http\Controllers\Association\ExpenseController;
use App\Http\Controllers\Association\OrphanController as AssociationOrphanController;
use App\Http\Controllers\Association\ResearcherController;
use App\Http\Controllers\Association\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrphanController;
use App\Http\Controllers\Researcher\ResearcherController as ResearcherResearcherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:association')->prefix('association')->name('association.')->group(function () {
    Route::resource('/researcher', ResearcherController::class);
    Route::get('/register/orphan', [AssociationOrphanController::class, 'registeredOrphan'])->name('orphan.register');
    Route::get('/candidate/orphan', [AssociationOrphanController::class, 'candidateOrphan'])->name('orphan.candidate');
    Route::get('/auditor/orphan', [AssociationOrphanController::class, 'auditorOrphan'])->name('orphan.auditor');
    Route::get('/certified/orphan', [AssociationOrphanController::class, 'certifiedOrphan'])->name('orphan.certified');
    Route::get('/waiting/orphan', [AssociationOrphanController::class, 'waitingOrphan'])->name('orphan.waiting');
    Route::get('/sponsored/orphan', [AssociationOrphanController::class, 'sponsoredOrphan'])->name('orphan.sponsored');
    Route::get('/archived/orphan', [AssociationOrphanController::class, 'archivedOrphan'])->name('orphan.archived');
    Route::post('/review', [ReviewController::class, 'associationReview'])->name('orphan.review');
    Route::resource('/orphan', AssociationOrphanController::class);

    Route::get('/orphans/{orphan}/edit', [OrphanController::class, 'edit'])->name('orphans.edit');
    Route::put('/orphans/{orphan}', [OrphanController::class, 'update'])->name('orphans.update');

    Route::get('view/orphan/sponsorship/{orphan}', [AssociationOrphanController::class, 'SponsorshipView'])->name('orphan.view.sponsorship');
    Route::get('view/orphan/{orphan}', [ResearcherResearcherController::class, 'view'])->name('orphan.view1');

    Route::get('/expenses/active', [ExpenseController::class, 'makeActive'])->name('expenses.active');
    Route::resource('/expenses', ExpenseController::class);

    Route::get('/message', [MessageController::class, 'message'])->name('message.index');
    Route::post('/message/store/{id}', [MessageController::class, 'activeMessage'])->name('message.store');
    Route::delete('/message/delete/{id}', [MessageController::class, 'deleteMessage'])->name('message.delete');
    Route::get('/notification', [NotificationController::class, 'AssociationNotification'])->name('notification');
});
