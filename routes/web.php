<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Admin\AdController as AdminAdController;
use App\Http\Controllers\Admin\AssociationController as AdminAssociationController;
use App\Http\Controllers\Admin\OrphanController as AdminOrphanController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Association\AssociationController;
use App\Http\Controllers\Association\ExpenseController;
use App\Http\Controllers\Association\OrphanController as AssociationOrphanController;
use App\Http\Controllers\Association\ResearcherController;
use App\Http\Controllers\Association\ReviewController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\QuestionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrphanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Researcher\ResearcherController as ResearcherResearcherController;
use App\Http\Controllers\Sponsor\MessageController as SponsorMessageController;
use App\Http\Middleware\MarkNotificationAsRead;
use App\Models\Association;
use App\Models\Orphan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Report\SponsorController as ReportSponsorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\SponsorEmailController;
use App\Http\Controllers\OrphanMediaController;




use App\Http\Controllers\Front\ExcelController;





// use App\Imports\GiftsImport;
// use Maatwebsite\Excel\Facades\Excel;

// Route::get('/import-gifts', function () {

//     Excel::import(
//         new GiftsImport,
//         'C:\Users\HP\Desktop\gifts.xlsx'
//     );

//     return 'تم الاستيراد';
// });


require __DIR__.'/front.php';
require __DIR__.'/orphan.php';
require __DIR__.'/association.php';
require __DIR__.'/researcher.php';
require __DIR__.'/admin.php';
require __DIR__.'/sponsor.php';
require __DIR__.'/shared.php';

require __DIR__.'/auth.php';
