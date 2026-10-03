<?php

use App\Models\TerraRequest;
use App\Support\TerraDemo;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('terra.home', [
    'announcements' => TerraDemo::announcements(),
]))->name('home');

Route::get('/services', fn () => view('terra.services', [
    'services' => TerraDemo::services(),
]))->name('services');

Route::get('/actualites', fn () => view('terra.actualites', [
    'announcements' => TerraDemo::announcements(),
]))->name('actualites');

Route::view('/contact', 'terra.contact')->name('contact');

Route::middleware('auth')->get('/mon-espace', function () {
    $requests = TerraRequest::query()
        ->where('user_id', auth()->id())
        ->latest()
        ->get()
        ->map(fn (TerraRequest $request) => [
            'ref' => $request->request_code,
            'subject' => $request->service,
            'status' => match ($request->status) {
                'completed' => 'traite',
                'in_progress' => 'en-cours',
                default => 'nouveau',
            },
            'date' => $request->created_at,
        ]);

    return view('terra.espace', compact('requests'));
})->name('espace');

require __DIR__.'/settings.php';
