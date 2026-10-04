<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
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

// Alertes en direct (JSON) pour l'accueil
Route::get('/alertes/actives', [AlertController::class, 'active'])
    ->middleware('throttle:60,1')
    ->name('alerts.active');

// Demandes des habitants : consultation publique
Route::get('/demandes', [ReportController::class, 'index'])->name('demandes.index');
Route::get('/demandes/{report:reference}', [ReportController::class, 'show'])->name('demandes.show');

// ---- Habitant connecté ----
Route::middleware('auth')->group(function () {
    Route::get('/signalement', [ReportController::class, 'create'])->name('signalement');
    Route::post('/signalement', [ReportController::class, 'store'])->middleware('throttle:10,1')->name('signalement.store');
    Route::post('/demandes/{report:reference}/soutenir', [ReportController::class, 'support'])->middleware('throttle:20,1')->name('demandes.support');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.store');
// Protection des formulaires contre le spam/bots (3 soumissions/min)
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact-form')
    ->name('contact.store');

require __DIR__.'/settings.php';
