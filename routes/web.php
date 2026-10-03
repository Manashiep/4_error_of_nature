<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransportController;
use Illuminate\Support\Facades\Route;

// ---- Pages publiques ----
Route::get('/', HomeController::class)->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/transports', TransportController::class)->name('transports');
Route::get('/actualites', [AnnouncementController::class, 'index'])->name('annonces.index');
Route::get('/actualites/{announcement}', [AnnouncementController::class, 'show'])->name('annonces.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

// ---- Habitant connecté ----
Route::middleware('auth')->group(function () {
    Route::get('/signalement', [ReportController::class, 'create'])->name('signalement');
    Route::post('/signalement', [ReportController::class, 'store'])->middleware('throttle:10,1')->name('signalement.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
