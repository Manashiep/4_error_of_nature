<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UserDataController;
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

// Protection du formulaire de contact contre le spam/bots (Honeypot + Rate Limit)
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact-form')
    ->name('contact.store');

// ---- Alertes de la ville ----
Route::get('/alertes', [AlertController::class, 'index'])->name('alerts.index');
Route::get('/alertes/actives', [AlertController::class, 'active'])
    ->middleware('throttle:60,1')
    ->name('alerts.active');
Route::get('/alertes/{alert}', [AlertController::class, 'show'])
    ->whereNumber('alert')
    ->name('alerts.show');

// Demandes des habitants : consultation publique
Route::get('/demandes', [ReportController::class, 'index'])->name('demandes.index');
Route::get('/demandes/{report:reference}', [ReportController::class, 'show'])->name('demandes.show');

// ---- Authentification & Vérification 2FA ----
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('login.store');

Route::middleware(['auth'])->group(function () {
    // Écran et validation du code 2FA (OTP)
    Route::get('/verify-2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/verify-2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::get('/resend-2fa', [TwoFactorController::class, 'resend'])->name('2fa.resend');
});

// ---- Espace Habitant connecté ----
Route::middleware(['auth'])->group(function () {
    Route::get('/signalement', [ReportController::class, 'create'])->name('signalement');
    Route::post('/signalement', [ReportController::class, 'store'])->middleware('throttle:10,1')->name('signalement.store');
    Route::post('/demandes/{report:reference}/soutenir', [ReportController::class, 'support'])->middleware('throttle:20,1')->name('demandes.support');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Transparence & Droit d'accès RGPD (Export des données personnelles - Req. F42/F43)

});

// Espace Tableau de bord
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
Route::get('/verify-2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
Route::post('/verify-2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');

require __DIR__.'/settings.php';
