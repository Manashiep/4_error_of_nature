@extends('layouts.public', ['title' => 'Connexion'])

@section('content')
<x-public.breadcrumb :items="[['Connexion']]" />
<x-public.panel class="mx-auto my-6 max-w-[30rem] p-6" aria-labelledby="titre">
    <h1 id="titre" class="font-hud text-[1.6rem] font-black tracking-[.04em]">Connexion</h1>
    <p class="mt-1 text-[.95rem] text-mute">Retrouvez votre espace personnel et vos démarches.</p>

    @if (session('status'))
        <p role="status" class="mt-3 text-ok hc:text-white">{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <p role="alert" class="mt-3 rounded-xl border-2 border-[#ff8aa3] p-3 hc:border-white">Connexion impossible : vérifiez les champs signalés ci-dessous.</p>
    @endif

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf
        <x-public.field name="email" label="Adresse e-mail" type="email" required autocomplete="email" />
        <x-public.field name="password" label="Mot de passe" type="password" required autocomplete="current-password" />
        <label class="mt-3 flex items-center gap-2 text-[.95rem]">
            <input type="checkbox" name="remember" value="1" class="size-4 accent-pink-neon" @checked(old('remember'))> Rester connecté
        </label>
        <x-public.button class="mt-4 w-full text-center">Se connecter</x-public.button>
    </form>

    <p class="mt-4 text-center text-mute">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-cyan-neon underline">Mot de passe oublié ?</a><br>
        @endif
        Pas encore de compte ? <a href="{{ route('register') }}" class="text-cyan-neon underline">Créer mon compte</a>
    </p>
</x-public.panel>
@endsection
