@extends('layouts.public', ['title' => 'Transport'])

@section('description', 'Bus, navettes, horaires et état du trafic de Nova Terra.')

@section('content')
<x-public.breadcrumb :items="[['Transport']]" />
<x-public.page-hero title="Transport" lead="Se déplacer dans la ville : lignes, horaires, état du trafic et services de transport." />

{{-- Bandeau d'alerte trafic --}}
@if ($disrupted->isNotEmpty())
    <div role="alert" class="my-6 rounded-2xl border-2 border-warn bg-warn/15 p-5 hc:border-white hc:bg-black">
        <h2 class="font-hud text-[1.05rem] font-medium">
            <span aria-hidden="true">⚠</span>
            {{ $disrupted->count() }} ligne(s) concernée(s) par une perturbation
        </h2>
        <ul class="mt-2 grid gap-1">
            @foreach ($disrupted as $d)
                <li><b>{{ $d->code }}</b> · {{ $d->name }} : {{ $d->status_label }}@if ($d->status_message) — {{ $d->status_message }}@endif</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Annonces liées aux transports --}}
@if ($alerts->isNotEmpty())
    <x-public.panel class="my-6 p-6" aria-labelledby="h-al">
        <h2 id="h-al" class="mb-4 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
            Travaux et alertes
            <a href="{{ route('annonces.index') }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Toutes les annonces →</a>
        </h2>
        <ul class="grid gap-3 md:grid-cols-3">
            @foreach ($alerts as $a)
                <li class="rounded-2xl border border-edge bg-glass p-4 hc:border-2 hc:bg-black">
                    <time datetime="{{ $a->published_at->toDateString() }}" class="text-[.85rem] font-bold text-cyan">{{ $a->published_at->locale('fr')->translatedFormat('j M Y') }}</time>
                    <span class="ml-2 inline-block rounded-full border px-2.5 py-0.5 text-[.75rem] {{ $a->level === 'alerte' ? 'border-coral text-coral' : 'border-warn text-warn' }}">{{ $a->category }}</span>
                    <h3 class="mt-1 font-bold"><a href="{{ route('annonces.show', $a->slug) }}" class="text-ink no-underline hover:text-cyan hover:underline hc:underline">{{ $a->title }}</a></h3>
                    <p class="mt-1 line-clamp-2 text-[.95rem] text-mute">{{ $a->summary }}</p>
                </li>
            @endforeach
        </ul>
    </x-public.panel>
@endif

{{-- Services de transport --}}
<x-public.panel class="my-6 p-6" aria-labelledby="h-tr">
    <h2 id="h-tr" class="mb-5 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
        Les services de transport
        <a href="{{ route('services.index', ['cat' => 'Transport']) }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Voir dans la liste des services →</a>
    </h2>

    @php $chip = 'block rounded-full border border-edge px-4 py-1.5 text-ink no-underline hover:bg-glass-hi aria-[current=true]:border-cyan aria-[current=true]:bg-cyan/15 hc:border-2 hc:aria-[current=true]:bg-[#ffe600] hc:aria-[current=true]:text-black'; @endphp
    @if ($types->count() > 1)
        <nav aria-label="Types de transport">
            <ul class="mb-5 flex flex-wrap gap-2">
                <li><a href="{{ route('transport') }}" @if(! $type) aria-current="true" @endif class="{{ $chip }}">Toutes</a></li>
                @foreach ($types as $t)
                    <li><a href="{{ route('transport', ['type' => $t]) }}" @if($type === $t) aria-current="true" @endif class="{{ $chip }}">{{ $t }}</a></li>
                @endforeach
            </ul>
        </nav>
    @endif

    @if ($lines->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">Aucune ligne de transport n'est publiée pour le moment.</p>
    @else
        <ul class="grid gap-4 lg:grid-cols-2">
            @foreach ($lines as $l)
                <li class="flex flex-col gap-3 rounded-3xl border border-edge bg-glass p-5 hc:border-2 hc:bg-black">
                    <div class="flex flex-wrap items-center gap-2 text-[.8rem]">
                        <span class="rounded-full border border-cyan/60 px-3 py-0.5 font-bold text-cyan">{{ $l->code }}</span>
                        <span class="rounded-full border border-edge px-3 py-0.5 text-mute">{{ $l->type }}</span>
                        <span class="rounded-full border px-3 py-0.5 {{ $l->status_classes }}">{{ $l->status_label }}</span>
                    </div>

                    <h3 class="font-hud text-[1.05rem] font-medium leading-snug">{{ $l->name }}</h3>

                    @if ($l->frequency)
                        <p class="text-[.95rem]"><b class="text-cyan">Fréquence :</b> {{ $l->frequency }}</p>
                    @endif

                    @if ($l->route_description)
                        <p class="text-mute">{{ $l->route_description }}</p>
                    @endif

                    @if ($l->status !== 'Normal' && $l->status_message)
                        <p class="rounded-xl border border-warn/70 bg-warn/10 p-3 text-[.95rem]">{{ $l->status_message }}</p>
                    @endif

                    @if (! empty($l->schedules))
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[.92rem]">
                                <caption class="sr-only">Horaires de la ligne {{ $l->code }}</caption>
                                <thead>
                                    <tr class="border-b border-edge text-mute">
                                        <th scope="col" class="py-1.5 pr-3 font-medium">Jours</th>
                                        <th scope="col" class="py-1.5 font-medium">Horaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($l->schedules as $row)
                                        <tr class="border-b border-edge/50 align-top">
                                            <th scope="row" class="py-1.5 pr-3 font-bold">{{ $row['days'] ?? '' }}</th>
                                            <td class="py-1.5">{{ $row['hours'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @if ($services->isNotEmpty())
        <div class="mt-8 border-t border-edge pt-6">
            <h3 class="mb-4 font-hud text-[1rem] font-medium">Autres services liés au transport</h3>
            <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $s)
                    <x-public.service-card :service="$s" />
                @endforeach
            </ul>
        </div>
    @endif
</x-public.panel>

{{-- Signalement --}}
<x-public.panel class="my-6 p-6" aria-labelledby="h-pb">
    <h2 id="h-pb" class="mb-2 font-hud text-[1.1rem] font-medium">Un problème sur la route ou dans les transports ?</h2>
    <p class="mb-4 max-w-[46rem] text-mute">Un arrêt abîmé, une route dégradée, un feu en panne : dites-nous où, nous transmettons au bon service.</p>
    <a href="{{ route('signalement') }}"
       class="inline-flex items-center gap-2 rounded-full bg-red-600 px-6 py-3 font-bold text-white no-underline shadow-lg shadow-red-600/30 transition hover:-translate-y-0.5 hover:bg-red-700 hc:border-2 hc:border-white hc:bg-black">
        <span aria-hidden="true">⚠</span> Signaler un problème
    </a>
</x-public.panel>
@endsection