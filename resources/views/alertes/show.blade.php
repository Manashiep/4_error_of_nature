@extends('layouts.public', ['title' => $alert->title])

@section('content')
@php
    $styles = [
        'alerte'    => 'border-red-500 bg-red-500/15',
        'important' => 'border-amber-400 bg-amber-400/15',
        'info'      => 'border-cyan bg-cyan/10',
    ];
    $labels = ['alerte' => 'Alerte', 'important' => 'Important', 'info' => 'Information'];
@endphp

<x-public.breadcrumb :items="[['Annonces', route('annonces.index')], ['Alertes', route('alerts.index')], [\Illuminate\Support\Str::limit($alert->title, 40)]]" />
<x-public.page-hero :title="$alert->title" :lead="$alert->summary ?: 'Alerte de la ville'" />

<x-public.panel class="mx-auto my-6 max-w-[46rem] p-6" aria-labelledby="h-detail">
    <div class="mb-4 flex flex-wrap items-center gap-2 text-[.85rem]">
        <span class="rounded-full border-2 px-3 py-0.5 font-bold {{ $alert->is_expired ? 'border-edge' : ($styles[$alert->level] ?? $styles['info']) }}">{{ $labels[$alert->level] ?? 'Information' }}</span>
        <span class="text-mute">{{ $alert->category }}</span>
        <span class="text-mute">· Diffusée le {{ $alert->published_at->locale('fr')->translatedFormat('j F Y, H:i') }}</span>
        @if ($alert->expires_at)
            <span class="text-mute">· {{ $alert->is_expired ? 'Terminée le' : "Jusqu'au" }} {{ $alert->expires_at->locale('fr')->translatedFormat('j F Y, H:i') }}</span>
        @endif
    </div>

    @if ($alert->is_expired)
        <p role="status" class="mb-4 rounded-xl border border-edge bg-glass p-3 text-mute hc:border-2 hc:border-white hc:bg-black">Cette alerte est terminée. Elle reste affichée pour information.</p>
    @endif

    <h2 id="h-detail" class="mb-2 font-hud text-[1.05rem] font-medium">Détails et consignes</h2>
    @if ($alert->body)
        <div class="leading-relaxed">{!! nl2br(e($alert->body)) !!}</div>
    @else
        <p class="text-mute">Aucun détail supplémentaire n'a été publié pour cette alerte.</p>
    @endif

    <p class="mt-6"><a href="{{ route('alerts.index') }}" class="font-bold text-cyan hover:underline hc:underline">← Toutes les alertes</a></p>
</x-public.panel>

@if ($others->isNotEmpty())
    <x-public.panel class="mx-auto my-6 max-w-[46rem] p-6" aria-labelledby="h-autres">
        <h2 id="h-autres" class="mb-4 font-hud text-[1.05rem] font-medium">Autres alertes en cours</h2>
        <ul class="grid gap-3">
            @foreach ($others as $a)
                @include('partials.public.alert-card', ['a' => $a])
            @endforeach
        </ul>
    </x-public.panel>
@endif
@endsection