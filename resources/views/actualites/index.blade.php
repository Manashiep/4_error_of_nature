@extends('layouts.public', ['title' => 'Annonces de la ville'])

@section('content')
<x-public.breadcrumb :items="[['Annonces']]" />
<x-public.page-hero title="Annonces de la ville" lead="Annonces municipales, changements de service et informations pratiques." />

<x-public.panel class="my-[1.1rem] p-5" aria-labelledby="h-a">
    <h2 id="h-a" class="mb-[.9rem] font-hud text-[1.125rem] font-bold tracking-[.05em]">Publications récentes</h2>

    @if ($categories->count() > 1)
        <nav aria-label="Catégories d'annonces">
            <ul class="mb-4 flex flex-wrap gap-2">
                <li>
                    <a href="{{ url('/actualites') }}" @if(! $cat) aria-current="true" @endif
                       class="block rounded-full border border-white/35 px-[.9rem] py-[.3rem] text-white no-underline hover:border-cyan-neon aria-[current=true]:border-pink-neon aria-[current=true]:bg-pink-neon/10 hc:border-2 hc:aria-[current=true]:bg-[#ffe600] hc:aria-[current=true]:text-black">Toutes</a>
                </li>
                @foreach ($categories as $c)
                    <li>
                        <a href="{{ url('/actualites').'?categorie='.urlencode($c) }}" @if($cat === $c) aria-current="true" @endif
                           class="block rounded-full border border-white/35 px-[.9rem] py-[.3rem] text-white no-underline hover:border-cyan-neon aria-[current=true]:border-pink-neon aria-[current=true]:bg-pink-neon/10 hc:border-2 hc:aria-[current=true]:bg-[#ffe600] hc:aria-[current=true]:text-black">{{ $c }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    @endif

    <ul class="grid gap-3">
        @forelse ($items as $a)
            <li class="grid gap-1 rounded-xl border border-line bg-white/5 p-[.9rem] sm:grid-cols-[5.5rem_1fr] sm:gap-4 hc:border-2 hc:border-white hc:bg-black">
                <time datetime="{{ $a->published_at->toDateString() }}" class="font-hud text-[.85rem] font-bold text-cyan-neon">{{ $a->published_at->locale('fr')->translatedFormat('j M') }}</time>
                <div>
                    <span class="inline-block rounded-full border px-[.65rem] py-[.1rem] text-[.8rem] {{ $a->level === 'info' ? 'border-cyan-neon/50 text-cyan-neon' : 'border-[#ffc23d]/60 text-[#ffc23d] hc:border-white hc:text-white' }}">
                        {{ $a->level === 'alerte' ? 'Alerte' : ($a->level === 'important' ? 'Important' : $a->category) }}
                    </span>
                    <h3 class="mt-1 text-[1.2rem] font-semibold"><a href="{{ route('annonces.show', $a) }}" class="text-ink hover:text-cyan-neon hover:underline hc:underline">{{ $a->title }}</a></h3>
                    <p class="mt-[.2rem] text-mute">{{ $a->summary }}</p>
                </div>
            </li>
        @empty
            <li class="rounded-xl border border-dashed border-line p-7 text-center text-mute">Aucune annonce pour le moment.</li>
        @endforelse
    </ul>
</x-public.panel>
@endsection
