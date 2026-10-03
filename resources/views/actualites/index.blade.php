@extends('layouts.public', ['title' => 'Annonces de la ville'])

@section('content')
<x-public.breadcrumb :items="[['Annonces']]" />
<x-public.page-hero title="Annonces de la ville" lead="Annonces municipales, changements de service et informations pratiques." />

<x-public.panel class="my-6 p-6" aria-labelledby="h-a">
    <h2 id="h-a" class="mb-5 font-hud text-[1.1rem] font-medium">Publications récentes</h2>

    @php $chip = 'block rounded-full border border-edge px-4 py-1.5 text-ink no-underline hover:bg-glass-hi aria-[current=true]:border-cyan aria-[current=true]:bg-cyan/15 hc:border-2 hc:aria-[current=true]:bg-[#ffe600] hc:aria-[current=true]:text-black'; @endphp
    @if (isset($categories) && $categories->count() > 1)
        <nav aria-label="Catégories d'annonces">
            <ul class="mb-5 flex flex-wrap gap-2">
                <li><a href="{{ route('annonces.index') }}" @if(! $cat) aria-current="true" @endif class="{{ $chip }}">Toutes</a></li>
                @foreach ($categories as $c)
                    <li><a href="{{ route('annonces.index', ['categorie' => $c]) }}" @if($cat === $c) aria-current="true" @endif class="{{ $chip }}">{{ $c }}</a></li>
                @endforeach
            </ul>
        </nav>
    @endif

    <ul class="grid gap-3">
        @forelse ($items as $a)
            <li class="grid gap-3 rounded-2xl border border-edge bg-glass p-4 sm:grid-cols-[6rem_1fr] sm:gap-4 hc:border-2 hc:bg-black">
                {{-- Date à gauche --}}
                <time datetime="{{ $a->published_at ? $a->published_at->toDateString() : '' }}" class="text-[.85rem] font-bold text-cyan">
                    {{ $a->published_at ? $a->published_at->locale('fr')->translatedFormat('j M Y') : '' }}
                </time>

                <div class="grid gap-3 md:grid-cols-[1fr_10rem] items-center">
                    <div>
                        <span class="inline-block rounded-full border px-3 py-0.5 text-[.78rem] {{ isset($a->level) && $a->level === 'alerte' ? 'border-coral text-coral' : (isset($a->level) && $a->level === 'important' ? 'border-warn text-warn' : 'border-cyan/60 text-cyan') }}">
                            {{ $a->category }}
                        </span>
                        <h3 class="mt-1 text-[1.15rem] font-bold">
                            <a href="{{ route('annonces.show', $a->slug) }}" class="text-ink no-underline hover:text-cyan hover:underline hc:underline">
                                {{ $a->title }}
                            </a>
                        </h3>
                        <p class="mt-1 text-mute line-clamp-2">
                            {{ $a->summary ?? \Illuminate\Support\Str::limit(strip_tags($a->content), 120) }}
                        </p>
                    </div>

                    {{-- Image à droite --}}
                    @if ($a->image_url)
                        <img src="{{ $a->image_url }}"
                             alt="{{ $a->title }}"
                             loading="lazy"
                             class="h-24 w-full rounded-xl object-cover border border-edge/40 md:h-24">
                    @endif
                </div>
            </li>
        @empty
            <li class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">
                Aucune annonce pour le moment.
            </li>
        @endforelse
    </ul>

    @if ($items->hasPages())
        <nav aria-label="Pagination" class="mt-6 flex items-center justify-between gap-4">
            @if ($items->previousPageUrl()) <x-public.button variant="alt" :href="$items->previousPageUrl()">← Plus récentes</x-public.button> @else <span></span> @endif
            @if ($items->nextPageUrl()) <x-public.button variant="alt" :href="$items->nextPageUrl()">Plus anciennes →</x-public.button> @endif
        </nav>
    @endif
</x-public.panel>
@endsection
