@extends('layouts.public', ['title' => 'Créer mon compte'])

@section('content')
<x-public.breadcrumb :items="[['Inscription']]" />
<x-public.panel class="mx-auto my-6 max-w-[34rem] p-7" aria-labelledby="titre">
    <h1 id="titre" class="font-hud text-[1.6rem] font-light tracking-tight">Créer mon compte</h1>
    <p class="mt-2 text-[.95rem] text-mute">Un compte vous donne accès à votre espace personnel et au suivi de vos démarches. Les champs marqués d'une étoile sont obligatoires.</p>

    @if ($errors->any())
        <p role="alert" class="mt-4 rounded-2xl border-2 border-err p-3 hc:border-white">Le compte n'a pas été créé : corrigez les champs signalés ci-dessous.</p>
    @endif

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf
        <div class="grid gap-x-4 sm:grid-cols-2">
            <x-public.field name="firstname" label="Prénom" required autocomplete="given-name" />
            <x-public.field name="name" label="Nom" required autocomplete="family-name" />
        </div>
        <x-public.field name="email" label="Adresse e-mail" type="email" required autocomplete="email" />
        <x-public.field name="terrarian_chip_number" label="Numéro de puce terrarienne" autocomplete="off"
                        help="Facultatif : vous pourrez l'ajouter plus tard dans votre profil." />
        <x-public.field name="password" label="Mot de passe" type="password" required autocomplete="new-password" help="8 caractères minimum." />
        <x-public.field name="password_confirmation" label="Confirmer le mot de passe" type="password" required autocomplete="new-password" />
        <x-public.button class="mt-6 w-full">Créer mon compte</x-public.button>
    </form>

    <p class="mt-5 text-center text-mute">Déjà inscrit ? <a href="{{ route('login') }}" class="text-cyan underline">Me connecter</a></p>
</x-public.panel>
@endsection
