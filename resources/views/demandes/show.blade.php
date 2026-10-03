@extends('layouts.public', ['title' => $report->category.' – '.$report->location])

@section('content')
@php $n = $report->supports_count; @endphp
<x-public.breadcrumb :items="[['Demandes des habitants', '/demandes'], [$report->category]]" />

<x-public.panel as="article" class="my-4 p-7" aria-labelledby="titre">
    <p class="flex flex-wrap items-center gap-3 text-[.9rem]">
        <time datetime="{{ $report->created_at->toDateString() }}" class="font-bold text-cyan">Déposée le {{ $report->created_at->locale('fr')->translatedFormat('j F Y') }}</time>
        <span class="rounded-full border border-edge px-3 py-0.5 text-[.8rem]">{{ $report->category }}</span>
        <span class="rounded-full border px-3 py-0.5 text-[.8rem] {{ $report->status_tone }}">{{ $report->status_label }}</span>
    </p>
    <h1 id="titre" class="mt-3 font-hud text-[clamp(1.4rem,4vw,2.2rem)] font-light leading-[1.1] tracking-tight">{{ $report->category }}</h1>
    <p class="mt-2 text-[1.1rem] text-mute">Lieu : {{ $report->location }}</p>

    <div class="mt-5 grid max-w-[46rem] gap-3 text-[1.05rem] leading-relaxed">
        @foreach (preg_split("/\n{2,}/", trim($report->description)) as $para)
            @if ($para !== '') <p>{!! nl2br(e($para)) !!}</p> @endif
        @endforeach
    </div>

    {{-- Soutien : trace claire de la contribution --}}
    <section id="soutien" aria-labelledby="h-sout" class="mt-8 scroll-mt-4 rounded-3xl border border-edge bg-glass p-5 hc:border-2 hc:bg-black">
        <h2 id="h-sout" class="font-hud text-[1rem] font-medium">
            {{ $n }} habitant{{ $n > 1 ? 's' : '' }} soutien{{ $n > 1 ? 'nent' : 't' }} cette demande
        </h2>

        @if (session('supported'))
            <div id="confirmation" role="alert" class="mt-4 rounded-2xl border-2 border-ok bg-ok/10 p-4 hc:border-white hc:bg-black">
                <p class="font-bold text-ok">✔ Merci !</p>
                <p>{{ session('supported') }}</p>
            </div>
        @elseif (session('info'))
            <p role="status" class="mt-4 rounded-2xl border-2 border-warn p-4 hc:border-white">{{ session('info') }}</p>
        @endif

        <div class="mt-4">
            @if ($report->is_closed)
                <p class="text-mute">
                    {{ $report->status === 'resolved' ? 'Cette demande est résolue' : 'Cette demande a été rejetée' }} : elle ne peut plus être soutenue.
                </p>
            @elseif (! auth()->check())
                <p class="mb-3 text-mute">Connectez-vous pour soutenir cette demande.</p>
                <x-public.button :href="route('login')">Me connecter</x-public.button>
            @elseif ($mine)
                <p class="font-bold text-cyan">C'est votre demande : elle compte déjà.</p>
            @elseif ($supported)
                <p class="font-bold text-ok">✔ Vous soutenez cette demande.</p>
            @else
                <form method="POST" action="{{ route('demandes.support', $report) }}">
                    @csrf
                    <x-public.button>Soutenir cette demande</x-public.button>
                </form>
            @endif
        </div>
    </section>

    <p class="mt-7"><a href="{{ route('demandes.index') }}" class="text-cyan hover:underline hc:underline">← Toutes les demandes</a></p>
</x-public.panel>
@endsection