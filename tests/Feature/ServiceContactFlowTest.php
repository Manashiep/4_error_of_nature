<?php

use App\Filament\Resources\Contacts\Pages\EditContact;
use App\Models\Contact;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('a public service page asks citizens to sign in before requesting an appointment', function () {
    Service::create([
        'slug' => 'urbanisme',
        'name' => 'Urbanisme',
        'short_description' => 'Dossier urbanisme et permis.',
        'description' => 'Service dédié aux demandes d’urbanisme.',
        'category' => 'Administration',
        'is_active' => true,
        'is_featured' => true,
        'views_count' => 12,
        'contact_email' => 'urbanisme@nova-terra.fr',
        'contact_phone' => '+33101010101',
    ]);

    $response = $this->get(route('services.show', 'urbanisme'));

    $response->assertOk()
        ->assertSee('contacter ce service')
        ->assertSee('Connectez-vous pour demander un rendez-vous')
        ->assertSee(route('login'), false)
        ->assertDontSee('name="requested_at"', false);
});

test('a visitor can still send a regular question to the service', function () {
    $service = Service::create([
        'slug' => 'urbanisme',
        'name' => 'Urbanisme',
        'short_description' => 'Dossier urbanisme et permis.',
        'description' => 'Service dédié aux demandes d’urbanisme.',
        'category' => 'Administration',
        'is_active' => true,
        'is_featured' => true,
        'views_count' => 0,
    ]);

    $this->get(route('services.show', $service))
        ->assertOk()
        ->assertSee('Poser une question au service')
        ->assertSee('name="website_hp"', false)
        ->assertDontSee('name="requested_at"', false);

    $this->post(route('contact.store'), [
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'service' => $service->slug,
        'message' => 'Je souhaite obtenir un renseignement.',
    ])->assertRedirect(route('services.show', $service).'#contact-service');

    $this->assertDatabaseHas('contacts', [
        'type' => 'service',
        'service_id' => $service->id,
        'name' => 'Alice',
        'subject' => 'Question concernant Urbanisme',
        'message' => 'Je souhaite obtenir un renseignement.',
    ]);
});

test('an authenticated citizen can request an appointment with a service', function () {
    $service = Service::create([
        'slug' => 'urbanisme',
        'name' => 'Urbanisme',
        'short_description' => 'Dossier urbanisme et permis.',
        'description' => 'Service dédié aux demandes d’urbanisme.',
        'category' => 'Administration',
        'is_active' => true,
        'is_featured' => true,
        'views_count' => 0,
        'contact_email' => 'urbanisme@nova-terra.fr',
        'contact_phone' => '+33101010101',
    ]);

    $citizen = User::factory()->create([
        'name' => 'Alice',
        'email' => 'alice@example.com',
    ]);

    $response = $this->actingAs($citizen)->post(route('appointments.store', $service), [
        'requested_at' => now()->addDays(3)->setTime(14, 30)->format('Y-m-d\TH:i'),
        'message' => 'Je souhaite discuter de mon dossier de permis.',
    ]);

    $response->assertRedirect(route('services.show', $service).'#appointment-status');
    $this->assertDatabaseHas('contacts', [
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'type' => 'service',
        'service_id' => $service->id,
        'user_id' => $citizen->id,
        'status' => 'nouveau',
        'message' => 'Je souhaite discuter de mon dossier de permis.',
    ]);

    expect(Contact::firstOrFail()->requested_at)->not->toBeNull();
});

test('guests cannot submit an appointment and citizens cannot request a past timeslot', function () {
    $service = Service::create([
        'slug' => 'urbanisme',
        'name' => 'Urbanisme',
        'short_description' => 'Dossier urbanisme et permis.',
        'description' => 'Service dédié aux demandes d’urbanisme.',
        'category' => 'Administration',
        'is_active' => true,
        'is_featured' => true,
        'views_count' => 0,
    ]);

    $this->post(route('appointments.store', $service), [])
        ->assertRedirect(route('login'));

    $this->actingAs(User::factory()->create())
        ->post(route('appointments.store', $service), [
            'requested_at' => now()->subDay()->format('Y-m-d\TH:i'),
            'message' => 'Je souhaite discuter de mon dossier de permis.',
        ])
        ->assertSessionHasErrors('requested_at');

    expect(Contact::query()->count())->toBe(0);
});

test('agents can see the decision controls when editing a service appointment', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $appointment = Contact::create([
        'type' => 'service',
        'user_id' => User::factory()->create()->id,
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'subject' => 'Demande de rendez-vous',
        'message' => 'Je souhaite un rendez-vous avec le service.',
        'requested_at' => now()->addDays(3),
        'status' => 'nouveau',
    ]);

    $this->actingAs($agent)
        ->get('/admin/contacts/'.$appointment->id.'/edit')
        ->assertOk()
        ->assertSee('Décision')
        ->assertSee('Accepter le rendez-vous')
        ->assertSee('Refuser le rendez-vous');

    Livewire::actingAs($agent)
        ->test(EditContact::class, ['record' => $appointment->getKey()])
        ->fillForm([
            'status' => 'traite',
            'confirmed_at' => now()->addDays(4)->format('Y-m-d H:i:s'),
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($appointment->fresh()->status)->toBe('traite')
        ->and($appointment->fresh()->confirmed_at)->not->toBeNull();

    Livewire::actingAs($agent)
        ->test(EditContact::class, ['record' => $appointment->getKey()])
        ->fillForm([
            'status' => 'archive',
            'rejection_reason' => 'Le service est indisponible à cette date.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($appointment->fresh()->status)->toBe('archive')
        ->and($appointment->fresh()->rejection_reason)->toBe('Le service est indisponible à cette date.');

    $this->actingAs($appointment->user)
        ->get(route('dashboard'))
        ->assertSee('Refusé')
        ->assertSee('Le service est indisponible à cette date.');
});

test('the citizen dashboard displays confirmed appointments and optional refusal reasons only to their owner', function () {
    $citizen = User::factory()->create();
    $anotherCitizen = User::factory()->create();
    $service = Service::create([
        'slug' => 'urbanisme',
        'name' => 'Urbanisme',
        'short_description' => 'Dossier urbanisme et permis.',
        'description' => 'Service dédié aux demandes d’urbanisme.',
        'category' => 'Administration',
        'is_active' => true,
        'is_featured' => true,
        'views_count' => 0,
    ]);

    $acceptedAppointment = Contact::create([
        'type' => 'service',
        'service_id' => $service->id,
        'user_id' => $citizen->id,
        'name' => $citizen->name,
        'email' => $citizen->email,
        'subject' => 'Rendez-vous urbanisme accepté',
        'message' => 'Je souhaite parler de mon permis de construire.',
        'requested_at' => now()->addDays(2),
        'confirmed_at' => now()->addDays(4)->setTime(16, 30),
        'status' => 'traite',
    ]);
    Contact::create([
        'type' => 'service',
        'service_id' => $service->id,
        'user_id' => $citizen->id,
        'name' => $citizen->name,
        'email' => $citizen->email,
        'subject' => 'Rendez-vous urbanisme refusé',
        'message' => 'Je souhaite des conseils pour ma déclaration.',
        'requested_at' => now()->addDays(3),
        'status' => 'archive',
    ]);
    Contact::create([
        'type' => 'service',
        'service_id' => $service->id,
        'user_id' => $citizen->id,
        'name' => $citizen->name,
        'email' => $citizen->email,
        'subject' => 'Rendez-vous refusé avec explication',
        'message' => 'Je souhaite des conseils pour ma déclaration de travaux.',
        'requested_at' => now()->addDays(5),
        'status' => 'archive',
        'rejection_reason' => 'Le service est fermé à cette date.',
    ]);
    Contact::create([
        'type' => 'service',
        'service_id' => $service->id,
        'user_id' => $anotherCitizen->id,
        'name' => $anotherCitizen->name,
        'email' => $anotherCitizen->email,
        'subject' => 'Rendez-vous privé',
        'message' => 'Demande de rendez-vous privée.',
        'requested_at' => now()->addDays(3),
        'status' => 'archive',
        'rejection_reason' => 'Détail confidentiel.',
    ]);

    $response = $this->actingAs($citizen)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Mes rendez-vous de service')
        ->assertSee('Rendez-vous urbanisme accepté')
        ->assertSee('Accepté')
        ->assertSee('16:30')
        ->assertSee('Rendez-vous urbanisme refusé')
        ->assertSee('Refusé')
        ->assertSee('Le service est fermé à cette date.')
        ->assertDontSee('Détail confidentiel.')
        ->assertDontSee('Rendez-vous privé');

    $acceptedAppointment->update(['status' => 'archive']);
    Contact::query()
        ->where('user_id', $citizen->id)
        ->whereKeyNot($acceptedAppointment->id)
        ->delete();

    $this->actingAs($citizen)->get(route('dashboard'))
        ->assertSee('Refusé')
        ->assertDontSee('Motif :');
});
