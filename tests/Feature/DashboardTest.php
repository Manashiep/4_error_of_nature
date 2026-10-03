<?php

use App\Models\ContactMessage;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('citizens can log out from the dashboard menu', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('data-test="logout-button"', false)
        ->assertSee('action="'.route('logout').'"', false);

    $this->post(route('logout'))->assertRedirect('/');

    $this->assertGuest();
});

test('the dashboard shows only the authenticated citizen own reports and messages', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    Report::create([
        'reference' => 'SIG-OWN001',
        'user_id' => $user->id,
        'title' => 'Éclairage public',
        'category' => 'Éclairage public',
        'description' => 'Un lampadaire est en panne près de chez moi.',
        'location' => 'Rue des Lilas',
        'status' => 'in_progress',
    ]);

    ContactMessage::create([
        'reference' => 'MSG-OWN001',
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'service' => 'État civil',
        'message' => 'Je souhaite obtenir un renseignement sur une démarche.',
        'status' => 'traite',
    ]);

    Report::create([
        'reference' => 'SIG-OTHER',
        'user_id' => $anotherUser->id,
        'title' => 'Voirie',
        'category' => 'Voirie',
        'description' => 'Un trou est présent sur la chaussée.',
        'location' => 'Avenue Centrale',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Espace citoyen')
        ->assertSee('SIG-OWN001')
        ->assertSee('MSG-OWN001')
        ->assertSee('Éclairage public')
        ->assertSee('État civil')
        ->assertSee('Mes démarches')
        ->assertSee('>2<', false)
        ->assertSee('>1<', false)
        ->assertDontSee('Un trou est présent sur la chaussée.')
        ->assertDontSee('SIG-OTHER');
});