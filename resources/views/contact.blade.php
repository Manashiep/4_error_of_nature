@extends('layouts.public', ['title' => 'Contact'])

@section('content')
@php
    $sel = $services->firstWhere('slug', old('service', $selected));
    $withContact = $services->filter(fn ($s) => $s->contact_email || $s->contact_phone)->take(5);
@endphp
<x-public.breadcrumb :items="[['Contact']]" />
<x-public.page-hero title="Contacter les services municipaux" lead="Posez votre question ou décrivez votre difficulté. Vous recevrez une confirmation d'envoi." />

<div class="my-6 grid items-start gap-6 md:grid-cols-[minmax(0,1fr)_20rem]">
    <x-public.panel class="p-6" aria-labelledby="h-f">
        <h2 id="h-f" class="mb-2 font-hud text-[1.1rem] font-medium">Votre message</h2>

        {{-- D04 / D16 : confirmation claire après l'envoi --}}
        @if (session('sent'))
            <div id="confirmation" role="alert" class="mb-4 rounded-2xl border-2 border-ok bg-ok/10 p-4 hc:border-white hc:bg-black">
                <p class="font-bold text-ok">✔ Message envoyé.</p>
                <p>Les services municipaux vous répondront dès que possible. Numéro de suivi : <b>{{ session('sent') }}</b>. Inutile de renvoyer le message.</p>
            </div>
        @endif
        @if ($errors->any())
            <p role="alert" class="mb-2 rounded-2xl border-2 border-err p-3 hc:border-white">Le message n'a pas été envoyé : corrigez les champs signalés ci-dessous.</p>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" novalidate>
            @csrf

            {{-- Honeypot : champ piège invisible pour piéger les bots --}}
            <div style="display: none;" aria-hidden="true">
                <input type="text" name="website_hp" id="website_hp" tabindex="-1" autocomplete="off">
            </div>

            <x-public.field name="name" label="Nom" required autocomplete="name" :value="auth()->user()?->name" />
            <x-public.field name="email" label="Adresse e-mail" type="email" required autocomplete="email" :value="auth()->user()?->email" />
            <x-public.field name="service" label="Service concerné" type="select">
                <option value="">Je ne sais pas</option>
                @foreach ($services as $s)
                    <option value="{{ $s->slug }}" @selected(old('service', $selected) === $s->slug)>{{ $s->tr('name') }}</option>
                @endforeach
            </x-public.field>
            <x-public.field name="message" label="Message" type="textarea" required help="Décrivez votre question ou votre difficulté (10 caractères minimum)." />
            <x-public.button class="mt-6 w-full">Envoyer le message</x-public.button>
        </form>
    </x-public.panel>

    <x-public.panel as="aside" class="p-6" aria-labelledby="h-i">
        <h2 id="h-i" class="mb-4 font-hud text-[1rem] font-medium">{{ $sel ? 'Coordonnées du service' : 'Joindre directement' }}</h2>
        <ul class="grid gap-4 text-[.98rem]">
            @foreach ($sel ? collect([$sel]) : $withContact as $s)
                <li>
                    <a href="{{ route('services.show', $s->slug) }}" class="block font-bold text-cyan hover:underline hc:underline">{{ $s->tr('name') }}</a>
                    @if ($s->contact_phone) <a class="block text-ink hover:underline" href="tel:{{ preg_replace('/[^+\d]/', '', $s->contact_phone) }}">{{ $s->contact_phone }}</a> @endif
                    @if ($s->contact_email) <a class="block break-all text-ink hover:underline" href="mailto:{{ $s->contact_email }}">{{ $s->contact_email }}</a> @endif
                </li>
            @endforeach
            @if (! $sel && $withContact->isEmpty())
                <li class="text-mute">Utilisez le formulaire : votre message sera transmis au bon service.</li>
            @endif
        </ul>
    </x-public.panel>
</div>
@endsection
