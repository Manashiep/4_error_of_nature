@extends('layouts.public', ['title' => 'Contact'])

@section('content')
<x-public.breadcrumb :items="[['Contact']]" />
<x-public.page-hero title="Contacter les services municipaux" lead="Posez votre question ou décrivez votre difficulté. Vous recevrez une confirmation d'envoi." />

<div class="my-[1.1rem] grid items-start gap-[1.1rem] md:grid-cols-[minmax(0,1fr)_19rem]">
    <x-public.panel class="p-5" aria-labelledby="h-f">
        <h2 id="h-f" class="mb-2 font-hud text-[1.125rem] font-bold tracking-[.05em]">Votre message</h2>

        {{-- D04 / D16 : confirmation claire après l'envoi --}}
        @if (session('sent'))
            <div id="confirmation" role="alert" class="mb-4 rounded-xl border-2 border-ok p-4 hc:border-white">
                <p class="font-bold text-ok hc:text-white">✔ Message envoyé.</p>
                <p>Les services municipaux vous répondront dès que possible. Numéro de suivi : <b>{{ session('sent') }}</b>. Inutile de renvoyer le message.</p>
            </div>
        @endif
        @if ($errors->any())
            <p role="alert" class="mb-2 rounded-xl border-2 border-[#ff8aa3] p-3 hc:border-white">Le message n'a pas été envoyé : corrigez les champs signalés ci-dessous.</p>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" novalidate>
            @csrf
            <x-public.field name="name" label="Nom" required autocomplete="name" :value="auth()->user()?->name" />
            <x-public.field name="email" label="Adresse e-mail" type="email" required autocomplete="email" :value="auth()->user()?->email" />
            <x-public.field name="service" label="Service concerné" type="select">
                <option value="">Je ne sais pas</option>
                @foreach ($services as $s)
                    <option value="{{ $s['slug'] }}" @selected(old('service', $selected) === $s['slug'])>{{ $s['title'] }}</option>
                @endforeach
            </x-public.field>
            <x-public.field name="message" label="Message" type="textarea" required help="Décrivez votre question ou votre difficulté (10 caractères minimum)." />
            <x-public.button class="mt-4 w-full text-center">Envoyer le message</x-public.button>
        </form>
    </x-public.panel>

    <x-public.panel as="aside" class="p-5" aria-labelledby="h-i">
        <h2 id="h-i" class="mb-[.9rem] font-hud text-[1.125rem] font-bold tracking-[.05em]">Autres moyens</h2>
        <ul class="grid gap-3">
            <li><b class="block text-cyan-neon">Mairie de Nova Terra</b>Place du Dôme, secteur Centre</li>
            <li><b class="block text-cyan-neon">Horaires</b>Du lundi au vendredi, 8 h – 18 h</li>
            <li><b class="block text-cyan-neon">Urgence</b>Pour un incident grave, appelez le 112.</li>
        </ul>
    </x-public.panel>
</div>
@endsection
