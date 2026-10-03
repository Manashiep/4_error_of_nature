<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ---- Pages publiques (100 % dynamiques, données issues de la base) ----
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/services', 'services')->name('services.index');
    Route::get('/transport', 'transport')->name('transport');
    Route::get('/services/{service:slug}', 'service')->name('services.show');
    Route::get('/actualites', 'announcements')->name('annonces.index');
    Route::get('/actualites/{announcement:slug}', 'announcement')->name('annonces.show');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/langue/{code}', 'lang')->name('lang');
});
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

// Demandes des habitants : consultation publique
Route::get('/demandes', [ReportController::class, 'index'])->name('demandes.index');
Route::get('/demandes/{report:reference}', [ReportController::class, 'show'])->name('demandes.show');

// ---- Habitant connecté ----
Route::middleware('auth')->group(function () {
    Route::get('/signalement', [ReportController::class, 'create'])->name('signalement');
    Route::post('/signalement', [ReportController::class, 'store'])->middleware('throttle:10,1')->name('signalement.store');
    Route::post('/demandes/{report:reference}/soutenir', [ReportController::class, 'support'])->middleware('throttle:20,1')->name('demandes.support');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

require __DIR__.'/settings.php';