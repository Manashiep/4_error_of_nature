@extends('layouts.public', ['title' => $announcement->title])

@section('description', $announcement->summary)

@section('content')
<x-public.breadcrumb :items="[['Annonces', '/actualites'], [$announcement->title]]" />

<x-public.panel as="article" class="my-4 p-7" aria-labelledby="titre">
    <p class="flex flex-wrap items-center gap-3 text-[.9rem]">
        <time datetime="{{ $announcement->published_at->toDateString() }}" class="font-bold text-cyan">{{ $announcement->published_at->locale('fr')->translatedFormat('j F Y') }}</time>
        <span class="rounded-full border border-cyan/60 px-3 py-0.5 text-[.8rem] text-cyan">{{ $announcement->category }}</span>
        @if ($announcement->level !== 'info')
            <span class="rounded-full border px-3 py-0.5 text-[.8rem] {{ $announcement->level === 'alerte' ? 'border-coral text-coral' : 'border-warn text-warn' }}">{{ $announcement->level === 'alerte' ? 'Alerte' : 'Important' }}</span>
        @endif
    </p>
    <h1 id="titre" class="mt-3 font-hud text-[clamp(1.5rem,4vw,2.4rem)] font-light leading-[1.1] tracking-tight">{{ $announcement->title }}</h1>

    @if ($announcement->image_url)
        <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="mt-5 max-h-[24rem] w-full rounded-2xl object-cover">
    @endif

    <p class="mt-4 text-[1.15rem] text-mute">{{ $announcement->summary }}</p>

    <div class="mt-6 grid max-w-[46rem] gap-4 text-[1.05rem] leading-relaxed">
        @foreach (preg_split("/\n{2,}/", trim((string) $announcement->body)) as $para)
            @if ($para !== '') <p>{!! nl2br(e($para)) !!}</p> @endif
        @endforeach
    </div>

    <p class="mt-7"><a href="{{ route('annonces.index') }}" class="text-cyan hover:underline hc:underline">← Toutes les annonces</a></p>
</x-public.panel>

@if ($others->isNotEmpty())
    <x-public.panel class="my-6 p-6" aria-labelledby="h-o">
        <h2 id="h-o" class="mb-4 font-hud text-[1.1rem] font-medium">À lire aussi</h2>
        <ul class="grid gap-3 md:grid-cols-3">
            @foreach ($others as $o)
                <li class="rounded-2xl border border-edge bg-glass p-4 hc:border-2 hc:bg-black">
                    <time datetime="{{ $o->published_at->toDateString() }}" class="text-[.85rem] font-bold text-cyan">{{ $o->published_at->locale('fr')->translatedFormat('j M Y') }}</time>
                    <h3 class="mt-1 font-bold"><a href="{{ route('annonces.show', $o->slug) }}" class="text-ink no-underline hover:text-cyan hover:underline hc:underline">{{ $o->title }}</a></h3>
                    <p class="mt-1 line-clamp-2 text-[.95rem] text-mute">{{ $o->summary }}</p>
                </li>
            @endforeach
        </ul>
    </x-public.panel>
@endif
@endsection