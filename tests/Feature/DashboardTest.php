<?php

use App\Models\Contact;
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

test('citizens can paginate their complete request and appointment histories', function () {
    $user = User::factory()->create();

    for ($index = 1; $index <= 12; $index++) {
        $createdAt = now()->subDays($index);

        $report = Report::create([
            'reference' => 'SIG-HIST'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'title' => 'Signalement historique '.$index,
            'description' => 'Description de la demande.',
            'status' => 'pending',
        ]);
        $report->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();

        ContactMessage::create([
            'reference' => 'MSG-HIST'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'service' => 'Service historique '.$index,
            'message' => 'Message historique.',
            'status' => 'nouveau',
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        $appointment = Contact::create([
            'type' => 'service',
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'subject' => 'Rendez-vous historique '.$index,
            'message' => 'Demande de rendez-vous.',
            'requested_at' => now()->addDays($index),
            'status' => 'nouveau',
        ]);
        $appointment->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();
    }

    $this->actingAs($user)
        ->get(route('dashboard', ['activityPage' => 2, 'appointmentsPage' => 2]))
        ->assertOk()
        ->assertSee('Historique de mes démarches')
        ->assertSee('SIG-HIST06')
        ->assertSee('MSG-HIST06')
        ->assertSee('Rendez-vous historique 11')
        ->assertDontSee('SIG-HIST01')
        ->assertSee('activityPage=2', false)
        ->assertSee('appointmentsPage=2', false);
});

test('a citizen receives a database notification when their report status changes', function () {
    $user = User::factory()->create();
    $report = Report::create([
        'reference' => 'SIG-STATUS1',
        'user_id' => $user->id,
        'title' => 'Éclairage public',
        'description' => 'Un lampadaire est en panne près de chez moi.',
        'status' => 'pending',
    ]);

    $report->update(['status' => 'in_progress']);

    expect($user->unreadNotifications)->toHaveCount(1)
        ->and($user->unreadNotifications->first()->data)->toMatchArray([
            'report_reference' => 'SIG-STATUS1',
            'report_title' => 'Éclairage public',
            'old_status' => 'pending',
            'new_status' => 'in_progress',
            'status_label' => 'En cours',
        ]);
});

test('citizens can see and mark their notifications as read but cannot access another users notification', function () {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();

    $ownReport = Report::create([
        'reference' => 'SIG-OWN002',
        'user_id' => $user->id,
        'title' => 'Collecte des déchets',
        'description' => 'La collecte de déchets n’a pas eu lieu.',
        'status' => 'pending',
    ]);
    $ownReport->update(['status' => 'resolved']);
    $ownNotification = $user->unreadNotifications()->firstOrFail();

    $otherReport = Report::create([
        'reference' => 'SIG-OTHER2',
        'user_id' => $anotherUser->id,
        'title' => 'Voirie',
        'description' => 'Un trou est présent sur la chaussée.',
        'status' => 'pending',
    ]);
    $otherReport->update(['status' => 'rejected']);
    $otherNotification = $anotherUser->unreadNotifications()->firstOrFail();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Notifications')
        ->assertSee('>1<', false);

    $this->get(route('notifications.index'))
        ->assertOk()
        ->assertSee('Collecte des déchets')
        ->assertDontSee('SIG-OTHER2');

    $this->post(route('notifications.read', $otherNotification->id))
        ->assertNotFound();
    $this->post(route('notifications.read', $ownNotification->id))
        ->assertRedirect(route('notifications.index'));

    expect($user->fresh()->unreadNotifications)->toHaveCount(0)
        ->and($anotherUser->fresh()->unreadNotifications)->toHaveCount(1);
});
