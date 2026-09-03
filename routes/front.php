<?php

use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/waiting/orphan', [FrontController::class, 'showOrphanToSponsored'])->name('front.waiting.orphan');
Route::post('/contact/send', [FrontController::class, 'send'])->name('contact.send');
Route::get('/about', [FrontController::class, 'aboutUs'])->name('about.us');
