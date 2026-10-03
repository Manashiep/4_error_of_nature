@extends('layouts.public', ['title' => 'Demandes des habitants'])

@section('content')
<x-public.breadcrumb :items="[['Demandes des habitants']]" />
<x-public.page-hero title="Demandes des habitants" lead="Un problème a peut-être déjà été signalé par un voisin. Soutenez sa demande plutôt que d'en créer une autre : plus elle est soutenue, plus elle compte." />

<x-public.panel class="my-6 p-6" aria-labelledby="h-d">
    <h2 id="h-d" class="mb-5 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
        Demandes en cours
        <a href="{{ route('signalement') }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Mon problème n'est pas dans la liste →</a>
    </h2>

    @php $chip = 'block rounded-full border border-edge px-4 py-1.5 text-ink no-underline hover:bg-glass-hi aria-[current=true]:border-cyan aria-[current=true]:bg-cyan/15 hc:border-2 hc:aria-[current=true]:bg-[#ffe600] hc:aria-[current=true]:text-black'; @endphp
    <nav aria-label="Types de problème">
        <ul class="mb-5 flex flex-wrap gap-2">
            <li><a href="{{ route('demandes.index') }}" @if(! $cat) aria-current="true" @endif class="{{ $chip }}">Toutes</a></li>
            @foreach ($categories as $c)
                <li><a href="{{ route('demandes.index', ['categorie' => $c]) }}" @if($cat === $c) aria-current="true" @endif class="{{ $chip }}">{{ $c }}</a></li>
            @endforeach
        </ul>
    </nav>

    @if ($items->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">Aucune demande pour le moment.</p>
    @else
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $r)
                <x-public.report-card :report="$r" />
            @endforeach
        </ul>
    @endif

    @if ($items->hasPages())
        <nav aria-label="Pagination" class="mt-6 flex items-center justify-between gap-4">
            @if ($items->previousPageUrl()) <x-public.button variant="alt" :href="$items->previousPageUrl()">← Précédentes</x-public.button> @else <span></span> @endif
            @if ($items->nextPageUrl()) <x-public.button variant="alt" :href="$items->nextPageUrl()">Suivantes →</x-public.button> @endif
        </nav>
    @endif
</x-public.panel>
@endsection
