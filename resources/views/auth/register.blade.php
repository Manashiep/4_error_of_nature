@extends('layouts.public', ['title' => 'Créer mon compte'])

@section('content')
<x-public.breadcrumb :items="[['Inscription']]" />
<x-public.panel class="mx-auto my-6 max-w-[30rem] p-6" aria-labelledby="titre">
    <h1 id="titre" class="font-hud text-[1.6rem] font-black tracking-[.04em]">Créer mon compte</h1>
    <p class="mt-1 text-[.95rem] text-mute">Un compte vous donne accès à votre espace personnel et au suivi de vos démarches.</p>

    @if ($errors->any())
        <p role="alert" class="mt-3 rounded-xl border-2 border-[#ff8aa3] p-3 hc:border-white">Le compte n'a pas été créé : corrigez les champs signalés ci-dessous.</p>
    @endif

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf
        <x-public.field name="name" label="Nom complet" required autocomplete="name" />
        <x-public.field name="email" label="Adresse e-mail" type="email" required autocomplete="email" />
        <x-public.field name="password" label="Mot de passe" type="password" required autocomplete="new-password" help="8 caractères minimum." />
        <x-public.field name="password_confirmation" label="Confirmer le mot de passe" type="password" required autocomplete="new-password" />
        <x-public.button class="mt-4 w-full text-center">Créer mon compte</x-public.button>
    </form>

    <p class="mt-4 text-center text-mute">Déjà inscrit ? <a href="{{ route('login') }}" class="text-cyan-neon underline">Me connecter</a></p>
</x-public.panel>
@endsection
