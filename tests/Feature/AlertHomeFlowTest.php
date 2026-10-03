<?php

use App\Models\Alert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dynamic alerts are displayed on the home page and notify users when created', function () {
    $users = User::factory()->count(2)->create();

    $alert = Alert::create([
        'title' => 'Travaux sur la route principale',
        'summary' => 'La circulation sera perturbée lundi au vendredi.',
        'body' => 'Le centre-ville est concerné par des travaux de voirie.',
        'category' => 'Travaux',
        'level' => 'important',
        'is_active' => true,
        'published_at' => now(),
        'expires_at' => now()->addDays(2),
    ]);

    expect($users)->each(fn ($user) => $user->notifications()->exists())->toBeTruthy();

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Travaux et alertes')
        ->assertSee($alert->title);
});