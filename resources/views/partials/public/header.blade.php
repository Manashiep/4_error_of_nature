@php
    $nav = 'block rounded-full px-4 py-2 text-mute no-underline hover:bg-glass hover:text-ink hc:underline aria-[current=page]:bg-glass-hi aria-[current=page]:text-ink hc:aria-[current=page]:bg-[#ffe600] hc:aria-[current=page]:text-black';
@endphp
<x-public.panel as="header" class="mt-1 flex flex-wrap items-center justify-between gap-3 rounded-[2rem]! py-3 pl-5 pr-3">
    <a href="{{ url('/') }}" aria-label="Terra Nova, accueil" class="flex items-center gap-3 text-ink no-underline">
        <x-public.logo class="h-10 w-auto text-ink" />
        <span class="font-hud text-[1.05rem] font-medium tracking-[.14em]">TERRA NOVA</span>
    </a>

    <nav aria-label="Navigation principale">
        <ul class="flex flex-wrap items-center gap-1">
            <li><a class="{{ $nav }}" href="{{ url('/') }}" @if (request()->is('/')) aria-current="page" @endif>Accueil</a></li>
            <li><a class="{{ $nav }}" href="{{ route('services.index') }}" @if (request()->is('services*')) aria-current="page" @endif>Services</a></li>
            <li><a class="{{ $nav }}" href="{{ route('transport') }}" @if (request()->is('transport*')) aria-current="page" @endif>Transport</a></li>
            <li><a class="{{ $nav }}" href="{{ route('demandes.index') }}" @if (request()->is('demandes*')) aria-current="page" @endif>Demandes</a></li>
            <li><a class="{{ $nav }}" href="{{ route('annonces.index') }}" @if (request()->is('actualites*')) aria-current="page" @endif>Annonces</a></li>
            <li><a class="{{ $nav }}" href="{{ route('contact') }}" @if (request()->is('contact*')) aria-current="page" @endif>Contact</a></li>
            @auth
                <li><x-public.button :href="route('dashboard')" class="px-5! py-2!">Mon espace</x-public.button></li>
            @else
                <li><a class="{{ $nav }}" href="{{ route('login') }}">Connexion</a></li>
                <li><x-public.button :href="route('register')" class="px-5! py-2!">Créer un compte</x-public.button></li>
            @endauth
        </ul>
    </nav>
</x-public.panel>