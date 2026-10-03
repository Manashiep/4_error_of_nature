@extends('layouts.public', ['title' => 'Accueil'])

@section('content')
@php $latest = $annonces->first(); @endphp

{{-- D07 : où suis-je, que puis-je faire --}}
<section class="grid items-center gap-10 py-12 lg:grid-cols-[1.05fr_1fr]" aria-labelledby="titre">
    <div>
        <p class="mb-4 inline-block rounded-full border border-edge bg-glass px-4 py-1 text-[.9rem] text-cyan">Bienvenue à Nova Terra</p>
        <h1 id="titre" class="font-hud text-[clamp(2.1rem,5.2vw,3.8rem)] font-light leading-[1.08] tracking-tight">Bonjour, vous êtes sur le portail officiel de Nova Terra.</h1>
        <p class="mb-8 mt-5 max-w-[46ch] text-[1.1rem] text-mute">Ici, vous trouvez les services de la ville, vous signalez un problème dans votre rue, vous lisez les annonces et vous écrivez à la mairie, sans vous déplacer.</p>

        <form role="search" action="{{ route('services.index') }}" method="get" class="mb-5 flex flex-wrap gap-3">
            <label for="home-q" class="sr-only">Rechercher un service</label>
            <input id="home-q" type="search" name="q" autocomplete="off" placeholder="Rechercher un service…"
                   class="min-w-[12rem] flex-1 rounded-full border border-edge bg-glass px-5 py-3 text-ink placeholder:text-mute/70 hc:border-2 hc:border-white hc:bg-black">
            <x-public.button>Rechercher</x-public.button>
        </form>

        <div class="flex flex-wrap gap-3">
            {{-- NOUVEAU : bouton rouge de signalement --}}
            <a href="{{ route('signalement') }}"
               class="inline-flex items-center gap-2 rounded-full bg-red-600 px-6 py-3 font-bold text-white no-underline shadow-lg shadow-red-600/30 transition hover:-translate-y-0.5 hover:bg-red-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white hc:border-2 hc:border-white hc:bg-black">
                <span aria-hidden="true">⚠</span> Signaler un problème
            </a>
            <x-public.button variant="alt" :href="route('services.index')">Voir les services</x-public.button>
            @guest
                <x-public.button variant="alt" :href="route('register')">Créer mon compte</x-public.button>
            @else
                <x-public.button variant="alt" :href="route('dashboard')">Mon espace</x-public.button>
            @endguest
        </div>
    </div>

    {{-- La pile de verre : chiffres et contenus réels de la base --}}
    <div class="grid gap-4 lg:relative lg:block lg:h-[440px]" aria-label="Aperçu de la plateforme">
        <x-public.panel as="div" class="p-5 transition hover:-translate-y-1 lg:absolute lg:left-0 lg:top-0 lg:z-10 lg:w-[90%]">
            <p class="text-[.78rem] uppercase tracking-widest text-mute">À la une</p>
            @if ($latest)
                <h2 class="mt-1 line-clamp-2 font-hud text-[.95rem] font-medium leading-snug"><a href="{{ route('annonces.show', $latest->slug) }}" class="text-ink no-underline hover:text-cyan hc:underline">{{ $latest->title }}</a></h2>
                <p class="mt-2 line-clamp-2 text-[.95rem] text-mute">{{ $latest->summary }}</p>
                <time datetime="{{ $latest->published_at->toDateString() }}" class="mt-2 block text-[.85rem] text-cyan">{{ $latest->published_at->locale('fr')->translatedFormat('j F Y') }}</time>
            @else
                <p class="mt-2 text-mute">Aucune annonce publiée pour le moment.</p>
            @endif
        </x-public.panel>

        <x-public.panel as="div" class="p-5 transition hover:-translate-y-1 lg:absolute lg:right-0 lg:top-[36%] lg:z-20 lg:w-[60%]">
            <h2 class="font-hud text-[.9rem] font-medium">Services en ligne</h2>
            <p class="mt-1 font-hud text-[2.1rem] font-light">{{ $stats['services'] }}</p>
            <div class="mt-2 h-2 overflow-hidden rounded-full border border-edge bg-glass" role="img"
                 aria-label="{{ $stats['actifs'] }} services disponibles sur {{ $stats['services'] }}">
                <i class="block h-full rounded-full bg-linear-to-r from-violet to-cyan" style="width: {{ $stats['services'] ? round($stats['actifs'] / $stats['services'] * 100) : 0 }}%"></i>
            </div>
            <p class="mt-2 text-[.85rem] text-mute">{{ $stats['actifs'] }} disponible{{ $stats['actifs'] > 1 ? 's' : '' }} · {{ $stats['annonces'] }} annonce{{ $stats['annonces'] > 1 ? 's' : '' }} publiée{{ $stats['annonces'] > 1 ? 's' : '' }}</p>
        </x-public.panel>

        <x-public.panel as="div" class="p-5 transition hover:-translate-y-1 lg:absolute lg:bottom-0 lg:left-[4%] lg:z-30 lg:w-[56%]">
            <h2 class="font-hud text-[.9rem] font-medium">Les plus consultés</h2>
            <ol class="mt-3 grid gap-2 text-[.95rem]">
                @forelse ($topViewed as $t)
                    <li class="flex items-center justify-between gap-3">
                        <a href="{{ route('services.show', $t->slug) }}" class="truncate text-ink no-underline hover:text-cyan hc:underline">{{ $t->tr('name') }}</a>
                        <span class="shrink-0 text-[.8rem] text-mute">{{ $t->views_count }} vue{{ $t->views_count > 1 ? 's' : '' }}</span>
                    </li>
                @empty
                    <li class="text-mute">Aucun service pour le moment.</li>
                @endforelse
            </ol>
        </x-public.panel>
    </div>
</section>

{{-- D07 : que puis-je faire ici ? Quatre accès évidents --}}
<section class="my-6" aria-labelledby="h-faire">
    <h2 id="h-faire" class="mb-4 font-hud text-[1.1rem] font-medium">Que voulez-vous faire ?</h2>
    @php
        $actions = [
            ['Trouver un service', 'Santé, voirie, état civil… trouvez celui qui correspond à votre besoin.', route('services.index')],
            ['Signaler un problème', 'Un lampadaire cassé, une fuite : dites ce qui s\'est passé et où.', route('signalement')],
            ['Lire les annonces', 'Les informations de la ville, les changements de service.', route('annonces.index')],
            ['Contacter la mairie', 'Une question ? Envoyez un message et recevez une confirmation.', route('contact')],
        ];
    @endphp
    <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($actions as [$titre, $desc, $href])
            <li>
                <a href="{{ $href }}" class="flex h-full flex-col gap-2 rounded-3xl border border-edge bg-linear-to-br from-glass-hi to-glass p-5 text-ink no-underline backdrop-blur-[26px] transition hover:-translate-y-1 hc:border-2 hc:border-white hc:bg-black hc:bg-none">
                    <b class="font-hud text-[.95rem] font-medium leading-snug">{{ $titre }}</b>
                    <span class="text-mute">{{ $desc }}</span>
                    <span class="mt-auto pt-2 font-bold text-cyan">Y aller →</span>
                </a>
            </li>
        @endforeach
    </ul>
</section>

{{-- D05 / F28 : services principaux (prioritaires, puis les plus consultés) --}}
<x-public.panel class="my-6 p-6" aria-labelledby="h-srv">
    <h2 id="h-srv" class="mb-5 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
        Les principaux services
        <a href="{{ route('services.index') }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Tous les services →</a>
    </h2>
    @if ($services->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">Aucun service n'est publié pour le moment.</p>
    @else
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $s)
                <x-public.service-card :service="$s" />
            @endforeach
        </ul>
    @endif
</x-public.panel>

<div class="my-6 grid items-start gap-6 md:grid-cols-[minmax(0,1fr)_21rem]">
    {{-- D06 : dernières annonces --}}
    <x-public.panel class="p-6" aria-labelledby="h-news">
        <h2 id="h-news" class="mb-5 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
            Dernières annonces
            <a href="{{ route('annonces.index') }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Toutes les annonces →</a>
        </h2>
        <ul class="grid gap-3">
            @forelse ($annonces as $a)
                <li class="grid gap-1 rounded-2xl border border-edge bg-glass p-4 sm:grid-cols-[6rem_1fr] sm:gap-4 hc:border-2 hc:bg-black">
                    <time datetime="{{ $a->published_at->toDateString() }}" class="text-[.85rem] font-bold text-cyan">{{ $a->published_at->locale('fr')->translatedFormat('j M Y') }}</time>
                    <div>
                        <h3 class="text-[1.1rem] font-bold"><a href="{{ route('annonces.show', $a->slug) }}" class="text-ink no-underline hover:text-cyan hover:underline hc:underline">{{ $a->title }}</a></h3>
                        <p class="mt-1 text-mute">{{ $a->summary }}</p>
                    </div>
                </li>
            @empty
                <li class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">Aucune annonce pour le moment.</li>
            @endforelse
        </ul>
    </x-public.panel>

    {{-- D12 / F35 : première visite, étape par étape --}}
    <x-public.panel as="aside" class="p-6" aria-labelledby="h-how">
        <h2 id="h-how" class="mb-4 font-hud text-[1.1rem] font-medium">Première visite ?</h2>
        @php $profilUrl = \Illuminate\Support\Facades\Route::has('profile.edit') ? route('profile.edit') : route('dashboard'); @endphp
        <ol class="grid gap-4">
            <li><b class="block text-cyan">1. @guest Créez votre compte @else Votre compte est prêt @endguest</b>
                @guest <a href="{{ route('register') }}" class="text-ink underline">Quelques informations suffisent.</a> @else C'est fait, merci. @endguest</li>
            <li><b class="block text-cyan">2. Complétez votre profil</b>
                @guest Après la connexion, ajoutez vos informations.
                @else
                    @php $manque = collect(['firstname' => 'prénom', 'terrarian_chip_number' => 'numéro de puce'])->filter(fn ($l, $k) => blank(auth()->user()->$k))->values(); @endphp
                    @if ($manque->isEmpty()) ✔ Profil complet, merci.
                    @else Il manque : {{ $manque->implode(', ') }}. <a href="{{ $profilUrl }}" class="text-ink underline">Compléter mon profil</a>
                    @endif
                @endguest</li>
            <li><b class="block text-cyan">3. Trouvez un service</b><a href="{{ route('services.index') }}" class="text-ink underline">Parcourir les services</a></li>
            <li><b class="block text-cyan">4. Lancez votre démarche</b>Ouvrez le service, puis écrivez-nous ou signalez un problème. Vous recevez toujours une confirmation.</li>
        </ol>
        @guest
            <div class="mt-5 flex flex-wrap gap-3">
                <x-public.button :href="route('register')">Créer mon compte</x-public.button>
                <x-public.button variant="alt" :href="route('login')">Me connecter</x-public.button>
            </div>
        @else
            <x-public.button class="mt-5" :href="route('dashboard')">Ouvrir mon espace</x-public.button>
        @endguest
    </x-public.panel>
</div>

{{-- Demandes des habitants : soutenir une demande déjà déposée --}}
<x-public.panel class="my-6 p-6" aria-labelledby="h-dem">
    <h2 id="h-dem" class="mb-2 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
        Demandes des habitants
        <a href="{{ route('demandes.index') }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Toutes les demandes →</a>
    </h2>
    <p class="mb-5 max-w-[46rem] text-mute">Un problème déjà signalé par un voisin ? Soutenez sa demande plutôt que d'en créer une autre.</p>
    @if ($reports->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">Aucune demande pour le moment. <a href="{{ route('signalement') }}" class="text-cyan underline">Signaler un problème</a></p>
    @else
        <ul class="grid gap-4 md:grid-cols-3">
            @foreach ($reports as $r)
                <x-public.report-card :report="$r" />
            @endforeach
        </ul>
    @endif
</x-public.panel>

{{-- F21 / D20 / D14 : accessibilité et langues, pour tous les habitants --}}
<x-public.panel class="my-6 p-6" aria-labelledby="h-acc">
    <h2 id="h-acc" class="mb-2 font-hud text-[1.1rem] font-medium">Un portail pour tous les habitants</h2>
    <p class="mb-5 max-w-[46rem] text-mute">Les mêmes services, les mêmes pages et les mêmes démarches pour tout le monde, quel que soit votre outil ou votre langue.</p>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-edge bg-glass p-4 hc:border-2 hc:bg-black">
            <h3 class="font-bold text-cyan">Lecteur d'écran et clavier</h3>
            <p class="mt-1 text-[.95rem]">Pages organisées par titres, boutons et champs nommés, erreurs annoncées. Un lien « Aller au contenu » est proposé dès l'arrivée.</p>
        </div>
        <div class="rounded-2xl border border-edge bg-glass p-4 hc:border-2 hc:bg-black">
            <h3 class="font-bold text-cyan">Lisibilité</h3>
            <p class="mt-1 text-[.95rem]">En haut de chaque page : agrandir ou réduire le texte (A−, A, A+) et activer le contraste élevé.</p>
        </div>
        <div class="rounded-2xl border border-edge bg-glass p-4 hc:border-2 hc:bg-black">
            <h3 class="font-bold text-cyan">Votre langue</h3>
            @if (count($langs))
                <p class="mt-1 text-[.95rem]">Choisissez une langue pour lire les services :</p>
                <p class="mt-2 flex flex-wrap gap-2">
                    @foreach (array_unique(array_merge(['fr'], $langs)) as $code)
                        <a href="{{ route('lang', $code) }}" lang="{{ $code }}" hreflang="{{ $code }}" @if (session('lang', 'fr') === $code) aria-current="true" @endif
                           class="rounded-full border border-edge px-3 py-1 text-ink no-underline hover:bg-glass-hi aria-[current=true]:border-cyan aria-[current=true]:bg-cyan/15 hc:underline">{{ \App\Models\Service::languageLabel($code) }}</a>
                    @endforeach
                </p>
            @else
                <p class="mt-1 text-[.95rem]">Les contenus sont pour le moment disponibles en français.</p>
            @endif
        </div>
    </div>
</x-public.panel>

{{-- D13 : les mots difficiles, expliqués simplement (table glossary_terms) --}}
@if ($terms->isNotEmpty())
    <x-public.panel class="my-6 p-6" aria-labelledby="h-mots">
        <h2 id="h-mots" class="mb-4 font-hud text-[1.1rem] font-medium">Les mots simples</h2>
        <dl class="grid gap-4 md:grid-cols-2">
            @foreach ($terms as $t)
                <div class="rounded-2xl border border-edge bg-glass p-4 hc:border-2 hc:bg-black">
                    <dt class="font-bold text-cyan">{{ $t->term }}</dt>
                    <dd class="mt-1 text-[.95rem]">{{ $t->definition }}</dd>
                </div>
            @endforeach
        </dl>
    </x-public.panel>
@endif
@endsection