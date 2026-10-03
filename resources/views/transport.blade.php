@extends('layouts.public', ['title' => 'Transport'])

@section('description', 'Bus, voirie et déplacements : les services de transport de Nova Terra.')

@section('content')
<x-public.breadcrumb :items="[['Transport']]" />
<x-public.page-hero title="Transport" lead="Se déplacer dans la ville : retrouvez ici les services de transport et comment les utiliser." />

<x-public.panel class="my-6 p-6" aria-labelledby="h-tr">
    <h2 id="h-tr" class="mb-5 flex flex-wrap items-baseline justify-between gap-4 font-hud text-[1.1rem] font-medium">
        Les services de transport
        <a href="{{ route('services.index', ['cat' => 'Transport']) }}" class="font-sans text-[.95rem] font-medium text-cyan hover:underline hc:underline">Voir dans la liste des services →</a>
    </h2>

    @if ($services->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">
            Aucun service de transport n'est publié pour le moment.
            <a href="{{ route('contact') }}" class="text-cyan underline">Nous écrire</a>
        </p>
    @else
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $s)
                <x-public.service-card :service="$s" />
            @endforeach
        </ul>
    @endif
</x-public.panel>

<x-public.panel class="my-6 p-6" aria-labelledby="h-pb">
    <h2 id="h-pb" class="mb-2 font-hud text-[1.1rem] font-medium">Un problème sur la route ou dans les transports ?</h2>
    <p class="mb-4 max-w-[46rem] text-mute">Un arrêt abîmé, une route dégradée, un feu en panne : dites-nous où, nous transmettons au bon service.</p>
    <a href="{{ route('signalement') }}"
       class="inline-flex items-center gap-2 rounded-full bg-red-600 px-6 py-3 font-bold text-white no-underline shadow-lg shadow-red-600/30 transition hover:-translate-y-0.5 hover:bg-red-700 hc:border-2 hc:border-white hc:bg-black">
        <span aria-hidden="true">⚠</span> Signaler un problème
    </a>
</x-public.panel>
@endsection