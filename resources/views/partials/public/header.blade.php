@php
    $nav = 'block rounded-full border border-transparent px-[.8rem] py-[.35rem] text-ink no-underline hover:border-line aria-[current=page]:border-pink-neon aria-[current=page]:shadow-[0_0_12px_rgba(255,45,138,.35)]';
@endphp
<x-public.panel as="header" class="flex flex-wrap items-center justify-between gap-3 px-[1.1rem] py-3">
    <a href="{{ url('/') }}" aria-label="Nova Terra, accueil" class="flex items-center gap-[.65rem] text-ink no-underline">
        <span aria-hidden="true" class="grid size-10 place-items-center rounded-[.6rem] border border-pink-neon font-hud text-[.85rem] font-bold text-pink-neon shadow-[0_0_14px_#ff2d8a66]">&lt;/&gt;</span>
        <span>
            <b class="block font-hud text-base font-bold tracking-[.06em]">NOVA TERRA</b>
            <small class="text-[.8rem] text-cyan-neon">Portail des habitants</small>
        </span>
    </a>

    <nav aria-label="Navigation principale">
        <ul class="flex flex-wrap gap-[.4rem]">
            <li><a class="{{ $nav }}" href="{{ url('/') }}" @if(request()->is('/')) aria-current="page" @endif>Accueil</a></li>
            <li><a class="{{ $nav }}" href="{{ url('/services') }}" @if(request()->is('services*')) aria-current="page" @endif>Services</a></li>
            <li><a class="{{ $nav }}" href="{{ url('/transports') }}" @if(request()->is('transports*')) aria-current="page" @endif>Transports</a></li>
            <li><a class="{{ $nav }}" href="{{ url('/actualites') }}" @if(request()->is('actualites*')) aria-current="page" @endif>Actualités</a></li>
            <li><a class="{{ $nav }}" href="{{ url('/contact') }}" @if(request()->is('contact*')) aria-current="page" @endif>Contact</a></li>
            @auth
                <li><a class="{{ $nav }}" href="{{ route('dashboard') }}">Mon espace</a></li>
            @else
                <li><a class="{{ $nav }}" href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif>Connexion</a></li>
                <li><a class="{{ $nav }}" href="{{ route('register') }}" @if(request()->routeIs('register')) aria-current="page" @endif>Inscription</a></li>
            @endauth
        </ul>
    </nav>
</x-public.panel>
