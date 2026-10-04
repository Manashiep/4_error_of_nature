@extends('layouts.public', ['title' => 'Alertes de la ville'])

@section('content')
<x-public.breadcrumb :items="[['Annonces', route('annonces.index')], ['Alertes']]" />
<x-public.page-hero title="Alertes de la ville" lead="Toutes les alertes diffusées par la mairie et les agents. Ouvrez une alerte pour lire les détails et les consignes." />

<x-public.panel class="my-6 p-6" aria-labelledby="h-cours">
    <h2 id="h-cours" class="mb-4 font-hud text-[1.1rem] font-medium">En cours ({{ $current->count() }})</h2>
    @if ($current->isEmpty())
        <p class="rounded-2xl border border-dashed border-edge p-8 text-center text-mute">Aucune alerte en cours pour le moment.</p>
    @else
        <ul class="grid gap-3">
            @foreach ($current as $a)
                @include('partials.public.alert-card', ['a' => $a])
            @endforeach
        </ul>
    @endif
</x-public.panel>

@if ($past->isNotEmpty())
    <x-public.panel class="my-6 p-6" aria-labelledby="h-passees">
        <h2 id="h-passees" class="mb-4 font-hud text-[1.1rem] font-medium">Alertes passées</h2>
        <ul class="grid gap-3">
            @foreach ($past as $a)
                @include('partials.public.alert-card', ['a' => $a])
            @endforeach
        </ul>
        <div class="mt-5">{{ $past->links() }}</div>
    </x-public.panel>
@endif
@endsection