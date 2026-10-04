<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(Request $request): View
    {
        $services = Service::orderBy('name')->get();
        $selected = $services->pluck('slug')->contains($request->query('service')) ? $request->query('service') : null;

        return view('contact', compact('services', 'selected'));
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. HONEYPOT : Si le champ piège est rempli, c'est un robot !
        if ($request->filled('website_hp')) {
            $serviceSlug = $request->input('service');
            $service = $serviceSlug ? Service::where('slug', $serviceSlug)->first() : null;

            // Feinte pour la page de détail d'un service
            if ($service) {
                return redirect(route('services.show', $service).'#contact-service')
                    ->with('contact_sent', true);
            }

            // Feinte pour la page de contact générale
            $fakeReference = 'MSG-'.strtoupper(Str::random(6));

            return redirect(route('contact').'#confirmation')
                ->with('sent', $fakeReference);
        }

        // 2. VALIDATION NORMALE
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'service' => ['nullable', 'exists:services,slug'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'required' => 'Le champ « :attribute » est obligatoire.',
            'email' => 'Saisissez une adresse e-mail valide.',
            'min.string' => 'Le champ « :attribute » doit contenir au moins :min caractères.',
            'max.string' => 'Le champ « :attribute » ne doit pas dépasser :max caractères.',
            'exists' => 'Choisissez un service dans la liste.',
        ], [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'service' => 'service concerné',
            'message' => 'message',
        ]);

        $service = $request->filled('service') ? Service::where('slug', $request->input('service'))->first() : null;
        $type = $service ? 'service' : 'general';

        // 3. ENREGISTREMENT BDD
        Contact::create([
            'type' => $type,
            'service_id' => $service?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $request->input('phone'),
            'subject' => $service
                ? 'Question concernant '.($service->tr('name') ?: $service->name)
                : 'Question générale',
            'message' => $data['message'],
            'status' => 'nouveau',
        ]);

        // 4. REDIRECTION & CONFIRMATION
        if ($service) {
            return redirect(route('services.show', $service).'#contact-service')->with('contact_sent', true);
        }

        $reference = 'MSG-'.strtoupper(Str::random(6));

        return redirect(route('contact').'#confirmation')->with('sent', $reference);
    }

    public function requestAppointment(Request $request, Service $service): RedirectResponse
    {
        abort_unless($service->is_active, 404);

        $data = $request->validate([
            'requested_at' => ['required', 'date', 'after:now'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'requested_at.required' => 'Choisissez la date et l’heure souhaitées.',
            'requested_at.date' => 'La date et l’heure indiquées ne sont pas valides.',
            'requested_at.after' => 'Le rendez-vous souhaité doit être dans le futur.',
            'message.required' => 'Précisez le motif du rendez-vous.',
            'message.min' => 'Le motif doit contenir au moins :min caractères.',
            'message.max' => 'Le motif ne doit pas dépasser :max caractères.',
        ]);

        Contact::create([
            'type' => 'service',
            'service_id' => $service->id,
            'user_id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'subject' => 'Demande de rendez-vous — '.($service->tr('name') ?: $service->name),
            'message' => $data['message'],
            'requested_at' => $data['requested_at'],
            'status' => 'nouveau',
        ]);

        return redirect(route('services.show', $service).'#appointment-status')
            ->with('appointment_requested', true);
    }
}
