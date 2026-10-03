<?php

use App\Models\Contact;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a public service page renders an inline contact form bound to the current service', function () {
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
        ->assertSee('Contacter ce service')
        ->assertSee('name="service"', false)
        ->assertSee('value="urbanisme"', false)
        ->assertSee('Envoyer un message')
        ->assertDontSee(route('contact', ['service' => 'urbanisme']));
});

test('a service-specific contact message is saved in the contact table with the service linked', function () {
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

    $response = $this->post(route('contact.store'), [
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'service' => 'urbanisme',
        'message' => 'Je souhaite obtenir un renseignement sur mon dossier.',
    ]);

    $response->assertRedirect(route('services.show', $service) . '#contact-service');
    $this->assertDatabaseHas('contacts', [
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'type' => 'service',
        'service_id' => $service->id,
    ]);
});
