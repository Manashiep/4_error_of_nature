@extends('layouts.public', ['title' => 'Accueil'])

@section('content')
{{-- D07 : où suis-je, que puis-je faire --}}
<x-public.panel class="my-4 px-6 py-9" aria-labelledby="titre">
    <span class="mb-[.9rem] inline-block rounded-full border border-line px-[.8rem] py-[.2rem] text-[.9rem] text-cyan-neon">
        Portail officiel de la ville de Nova Terra
    </span>
    <h1 id="titre" class="max-w-[44rem] font-hud text-[clamp(1.5rem,5vw,2.75rem)] font-black leading-[1.1] tracking-[.04em]">
        Tous les services de Nova Terra, au même endroit
    </h1>
    <p class="my-[.9rem] mb-5 max-w-[40rem] text-[1.25rem]">
        Signalez un problème, trouvez le bon service, lisez les annonces de la ville ou écrivez à la mairie, sans vous déplacer.
    </p>

    {{-- F32 : retrouver rapidement un service --}}
    <form role="search" action="{{ url('/services') }}" method="get" class="mb-4 flex flex-wrap gap-[.6rem]">
        <label for="home-q" class="sr-only">Rechercher un service</label>
        <input id="home-q" type="search" name="q" autocomplete="off"
               placeholder="Rechercher un service : santé, éclairage, état civil…"
               class="min-w-[12rem] flex-1 rounded-[.55rem] border border-[rgba(120,200,255,.45)] bg-white/5 px-3 py-[.6rem] text-ink placeholder:text-[#7f97c4] hc:border-2 hc:border-white hc:bg-black">
        <x-public.button>Rechercher</x-public.button>
    </form>

    {{-- D01 / D03 --}}
    <div class="flex flex-wrap gap-3">
        <x-public.button :href="url('/services')">Voir les services</x-public.button>
        @guest
            <x-public.button variant="alt" :href="route('register')">Créer mon compte</x-public.button>
            <x-public.button variant="alt" :href="route('login')">Me connecter</x-public.button>
        @else
            <x-public.button variant="alt" :href="route('dashboard')">Accéder à mon espace</x-public.button>
        @endguest
    </div>
</x-public.panel>

{{-- D05 / F28 : services principaux --}}
<x-public.panel class="my-[1.1rem] p-5" aria-labelledby="h-srv">
    <h2 id="h-srv" class="mb-[.9rem] flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.125rem] font-bold tracking-[.05em]">
        Que souhaitez-vous faire ?
        <a href="{{ url('/services') }}" class="font-sans text-[.95rem] font-medium tracking-normal text-cyan-neon hover:underline hc:underline">Tous les services</a>
    </h2>
    <ul class="grid grid-cols-[repeat(auto-fill,minmax(15rem,1fr))] gap-[.9rem]">
        @foreach ($services as $s)
            <li>
                <a href="{{ url($s['href']) }}"
                   class="flex h-full flex-col gap-[.35rem] rounded-xl border border-line bg-white/5 p-[1.1rem] text-ink no-underline transition hover:border-cyan-neon hover:shadow-[0_0_18px_rgba(56,232,255,.3)] hc:border-2 hc:border-white hc:bg-black">
                    <span aria-hidden="true" class="text-[1.9rem] leading-none">{{ $s['ico'] }}</span>
                    <b class="font-hud text-base font-bold tracking-[.03em]">{{ $s['titre'] }}</b>
                    <span class="text-mute">{{ $s['desc'] }}</span>
                    <span class="mt-auto font-bold text-cyan-neon">{{ $s['cta'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</x-public.panel>

<div class="my-[1.1rem] grid items-start gap-[1.1rem] md:grid-cols-[minmax(0,1fr)_21rem]">
    {{-- D06 : dernières annonces --}}
    <x-public.panel class="p-5" aria-labelledby="h-news">
        <h2 id="h-news" class="mb-[.9rem] flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.125rem] font-bold tracking-[.05em]">
            Dernières annonces
            <a href="{{ url('/actualites') }}" class="font-sans text-[.95rem] font-medium tracking-normal text-cyan-neon hover:underline hc:underline">Toutes les annonces</a>
        </h2>
        <ul class="grid gap-3">
            @forelse ($annonces as $a)
                @php $d = \Carbon\Carbon::parse($a['date'])->locale('fr'); @endphp
                <li class="grid gap-1 rounded-xl border border-line bg-white/5 p-[.9rem] sm:grid-cols-[5.5rem_1fr] sm:gap-4 hc:border-2 hc:border-white hc:bg-black">
                    <time datetime="{{ $d->toDateString() }}" class="font-hud text-[.85rem] font-bold text-cyan-neon">{{ $d->translatedFormat('j M') }}</time>
                    <div>
                        <h3 class="text-[1.2rem] font-semibold"><a href="{{ $a['url'] }}" class="text-ink hover:text-cyan-neon hover:underline hc:underline">{{ $a['titre'] }}</a></h3>
                        <p class="mt-[.2rem] text-mute">{{ $a['resume'] }}</p>
                    </div>
                </li>
            @empty
                <li class="rounded-xl border border-dashed border-line p-6 text-center text-mute">Aucune annonce pour le moment.</li>
            @endforelse
        </ul>
    </x-public.panel>

    {{-- Pour démarrer (visiteur) / Mon espace (connecté) --}}
    <x-public.panel as="aside" class="p-5" aria-labelledby="h-how">
        @guest
            <h2 id="h-how" class="mb-[.9rem] font-hud text-[1.125rem] font-bold tracking-[.05em]">Pour démarrer</h2>
            <ol class="grid gap-3">
                <li><b class="block text-cyan-neon">1. Créez votre compte</b>Quelques informations suffisent.</li>
                <li><b class="block text-cyan-neon">2. Accédez à votre espace</b>Vos démarches y sont réunies.</li>
                <li><b class="block text-cyan-neon">3. Suivez vos demandes</b>Chaque message reçoit une confirmation.</li>
            </ol>
            <x-public.button class="mt-4" :href="route('register')">Créer mon compte</x-public.button>
        @else
            <h2 id="h-how" class="mb-[.9rem] font-hud text-[1.125rem] font-bold tracking-[.05em]">Mon espace</h2>
            <p>Retrouvez vos demandes, leur état et l'historique de vos démarches.</p>
            <x-public.button class="mt-4" :href="route('dashboard')">Ouvrir mon espace</x-public.button>
        @endguest
    </x-public.panel>
</div>
@endsection
