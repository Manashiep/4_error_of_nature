@extends('layouts.public', ['title' => $announcement->title])

@section('description', $announcement->summary)

@section('content')
<x-public.breadcrumb :items="[['Annonces', '/actualites'], [$announcement->title]]" />

<x-public.panel as="article" class="my-4 p-6" aria-labelledby="titre">
    <p class="flex flex-wrap items-center gap-3 text-[.9rem]">
        <time datetime="{{ $announcement->published_at->toDateString() }}" class="font-hud font-bold text-cyan-neon">{{ $announcement->published_at->locale('fr')->translatedFormat('j F Y') }}</time>
        <span class="rounded-full border border-cyan-neon/50 px-[.65rem] py-[.1rem] text-[.8rem] text-cyan-neon">{{ $announcement->category }}</span>
    </p>
    <h1 id="titre" class="mt-2 font-hud text-[clamp(1.4rem,4vw,2.1rem)] font-black leading-[1.15] tracking-[.03em]">{{ $announcement->title }}</h1>
    <p class="mt-3 text-[1.2rem]">{{ $announcement->summary }}</p>

    @if ($announcement->action)
        <div class="mt-4 rounded-xl border-2 border-[#ffc23d] bg-[rgba(80,55,0,.55)] p-4 hc:border-white hc:bg-black">
            <h2 class="font-hud text-base font-bold tracking-[.04em]">Que faire</h2>
            <p class="mt-1">{{ $announcement->action }}</p>
        </div>
    @endif

    <div class="mt-5 grid max-w-[44rem] gap-3 text-[1.1rem]">
        @foreach (preg_split("/\n{2,}/", trim((string) $announcement->body)) as $para)
            @if ($para !== '') <p>{!! nl2br(e($para)) !!}</p> @endif
        @endforeach
    </div>

    <p class="mt-6"><a href="{{ url('/actualites') }}" class="text-cyan-neon hover:underline hc:underline">← Toutes les annonces</a></p>
</x-public.panel>
@endsection
