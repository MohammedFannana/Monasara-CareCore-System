<?php

use App\Http\Controllers\Admin\AdController as AdminAdController;
use App\Http\Controllers\Admin\AssociationController as AdminAssociationController;
use App\Http\Controllers\Admin\OrphanController as AdminOrphanController;
use App\Http\Controllers\Admin\Report\SponsorController as ReportSponsorController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\SponsorEmailController;
use App\Http\Controllers\Front\QuestionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Sponsor\MessageController as SponsorMessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('/association', AdminAssociationController::class);

    Route::middleware('can:show-admin')->group(function () {
        Route::resource('/sponsor', SponsorController::class);
        Route::get('orphan/certified', [AdminOrphanController::class, 'CertifiedOrphan'])->name('orphan.CertifiedOrphan');
        Route::get('orphan/unsponsored', [AdminOrphanController::class, 'UnsponsoredOrphan'])->name('orphan.UnsponsoredOrphan');
        Route::get('orphan/sponsored', [AdminOrphanController::class, 'SponsoredOrphan'])->name('orphan.SponsoredOrphan');
        Route::get('orphan/archived', [AdminOrphanController::class, 'ArchivedOrphan'])->name('orphan.ArchivedOrphan');
        Route::resource('/orphan', AdminOrphanController::class);
        Route::get('orphan/generate/waiting', [AdminOrphanController::class, 'generateWaiting'])->name('orphan.generate.waiting');
        Route::get('orphan/sponsorship/details/{orphan}', [AdminOrphanController::class, 'sponsorshipDetails'])->name('orphan.sponsorship');
        Route::get('orphan/transfer/show/{orphan}', [AdminOrphanController::class, 'orphanTransfer'])->name('orphan.transfer');
        Route::get('/notification', [NotificationController::class, 'AdminNotification'])->name('notification');
        Route::resource('/question', QuestionController::class);
        Route::get('message', [SponsorMessageController::class, 'AdminviewMessage'])->name('message.view');
        Route::resource('/ad', AdminAdController::class);

        Route::get('/sponsors/email', [SponsorEmailController::class, 'index'])->name('sponsors.email');
        Route::post('/sponsors/email/send', [SponsorEmailController::class, 'send'])->name('sponsors.email.send');
    });
});

Route::middleware('auth:web,association')->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('can:view-reports')->prefix('report')->name('report.')->group(function () {
        Route::get('sponsor', [ReportSponsorController::class, 'index'])->name('sponsor');
        Route::get('sponsorship', [ReportSponsorController::class, 'indexSponsorship'])->name('sponsorship');
        Route::get('orphan', [ReportSponsorController::class, 'indexOrphan'])->name('orphan');
        Route::get('gift', [ReportSponsorController::class, 'indexGift'])->name('gift');
        Route::get('financial', [ReportSponsorController::class, 'financial'])->name('financial');

        Route::post('excel', [ReportSponsorController::class, 'ExcelReport'])->name('excel');
        Route::post('pdf', [ReportSponsorController::class, 'PdfReport'])->name('pdf');
        Route::get('download/{id}', [ReportSponsorController::class, 'download'])->name('download');
        Route::delete('destroy/{id}', [ReportSponsorController::class, 'destroy'])->name('destroy');
    });
});
