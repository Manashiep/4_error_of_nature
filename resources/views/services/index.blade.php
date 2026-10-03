@extends('layouts.public', ['title' => 'Services'])

@section('content')
<x-public.breadcrumb :items="[['Services']]" />
<x-public.page-hero title="Services de la ville" lead="Choisissez la catégorie qui correspond à votre besoin, puis ouvrez le service pour voir comment faire." />

<x-public.panel class="my-6 p-6" aria-labelledby="h-l">
    <h2 id="h-l" class="mb-5 font-hud text-[1.1rem] font-medium">Tous les services</h2>

    {{-- F32 : recherche (fonctionne sans JavaScript) --}}
    <form role="search" method="get" action="{{ route('services.index') }}" class="mb-5 flex flex-wrap gap-3">
        <label class="sr-only" for="svc-q">Rechercher un service</label>
        <input id="svc-q" type="search" name="q" value="{{ $q }}" autocomplete="off" placeholder="Rechercher un service (ex. santé, voirie, état civil)"
               class="min-w-[12rem] flex-1 rounded-full border border-edge bg-glass px-5 py-3 text-ink placeholder:text-mute/70 hc:border-2 hc:border-white hc:bg-black">
        @if ($cat) <input type="hidden" name="cat" value="{{ $cat }}"> @endif
        <x-public.button>Rechercher</x-public.button>
    </form>

    @php $chip = 'block rounded-full border border-edge px-4 py-1.5 text-ink no-underline hover:bg-glass-hi aria-[current=true]:border-cyan aria-[current=true]:bg-cyan/15 hc:border-2 hc:aria-[current=true]:bg-[#ffe600] hc:aria-[current=true]:text-black'; @endphp
    <nav aria-label="Catégories">
        <ul class="mb-5 flex flex-wrap gap-2">
            <li><a href="{{ route('services.index', array_filter(['q' => $q])) }}" @if(! $cat) aria-current="true" @endif class="{{ $chip }}">Tous</a></li>
            @foreach ($categories as $c)
                <li><a href="{{ route('services.index', array_filter(['q' => $q, 'cat' => $c])) }}" @if($cat === $c) aria-current="true" @endif class="{{ $chip }}">{{ $c }}</a></li>
            @endforeach
        </ul>
    </nav>

    <p role="status" aria-live="polite" class="mb-4 text-[.9rem] text-mute">
        @if ($services->isEmpty())
            Aucun service ne correspond.
        @else
            {{ $services->count() }} {{ $services->count() > 1 ? 'services' : 'service' }}@if ($q !== '') pour « {{ $q }} »@endif
        @endif
    </p>

    @if ($services->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">
            Essayez un autre mot ou choisissez « Tous ». <a href="{{ route('services.index') }}" class="text-cyan underline">Voir tous les services</a>
        </p>
    @else
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $s)
                <x-public.service-card :service="$s" />
            @endforeach
        </ul>
    @endif
</x-public.panel>
@endsection
