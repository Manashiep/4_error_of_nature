@extends('layouts.public', ['title' => 'Transports'])

@section('content')
<x-public.breadcrumb :items="[['Services', '/services'], ['Transports']]" />
<x-public.page-hero title="Transports municipaux" lead="Horaires, fréquence et état du trafic de chaque ligne, sur une seule page." />

{{-- F36 : l'info utile en premier --}}
<x-public.panel class="my-[1.1rem] p-5" aria-labelledby="h-etat">
    <h2 id="h-etat" class="mb-2 font-hud text-[1.125rem] font-bold tracking-[.05em]">État du réseau</h2>
    <p role="status" class="text-[1.15rem]">
        @if ($disrupted === 0)
            <span class="font-bold text-ok hc:text-white">✔ Trafic normal</span> sur toutes les lignes.
        @else
            <span class="font-bold text-[#ffc23d] hc:text-white">⚠ {{ $disrupted }} {{ $disrupted > 1 ? 'lignes perturbées' : 'ligne perturbée' }}.</span> Les détails sont indiqués sur les lignes concernées.
        @endif
        <span class="block text-[.9rem] text-mute">Mis à jour à <time datetime="{{ $updatedAt->format('H:i') }}">{{ $updatedAt->format('H:i') }}</time>.</span>
    </p>
</x-public.panel>

<x-public.panel class="my-[1.1rem] p-5" aria-labelledby="h-lignes">
    <h2 id="h-lignes" class="mb-[.9rem] font-hud text-[1.125rem] font-bold tracking-[.05em]">Lignes</h2>
    <ul class="grid grid-cols-[repeat(auto-fill,minmax(19rem,1fr))] gap-3">
        @foreach ($lines as $l)
            <li id="ligne-{{ $l['code'] }}" class="scroll-mt-4 rounded-xl border border-line bg-white/5 p-4 target:border-cyan-neon hc:border-2 hc:border-white hc:bg-black">
                <div class="flex flex-wrap items-center gap-3">
                    <span aria-hidden="true" class="grid size-10 place-items-center rounded-lg font-hud text-[.85rem] font-bold text-black" style="background: {{ $l['color'] }}">{{ $l['code'] }}</span>
                    <h3 class="flex-1 text-[1.2rem] font-semibold">{{ $l['name'] }}</h3>
                    <span class="rounded-full border px-[.65rem] py-[.1rem] text-[.8rem] {{ $l['status'] === 'normal' ? 'border-ok/60 text-ok' : 'border-[#ffc23d]/70 text-[#ffc23d]' }} hc:border-white hc:text-white">
                        {{ $l['status'] === 'normal' ? 'Trafic normal' : 'Perturbée' }}
                    </span>
                </div>
                <p class="mt-2 text-mute">{{ $l['route'] }}</p>
                @if ($l['note'])
                    <p class="mt-2 rounded-lg border border-[#ffc23d]/60 p-2 text-[.95rem] hc:border-white">⚠ {{ $l['note'] }}</p>
                @endif
                <dl class="mt-3 grid grid-cols-[auto_1fr] gap-x-3 gap-y-1 text-[.95rem]">
                    <dt class="text-mute">Prochains départs</dt>
                    <dd class="font-bold text-cyan-neon">
                        @forelse ($l['next'] as $t) <time datetime="{{ $t }}">{{ $t }}</time>@if (! $loop->last) · @endif @empty Service terminé @endforelse
                    </dd>
                    <dt class="text-mute">Fréquence</dt>
                    <dd>toutes les {{ $l['headway_now'] }} min en ce moment</dd>
                    <dt class="text-mute">Heures de pointe</dt>
                    <dd>{{ $l['peak'] }} min · hors pointe {{ $l['offpeak'] }} min</dd>
                    <dt class="text-mute">Service</dt>
                    <dd>{{ $l['hours'] }}</dd>
                </dl>
            </li>
        @endforeach
    </ul>
    <p class="mt-4 text-[.9rem] text-mute">Départs depuis le terminus principal de chaque ligne. Une question ? <a href="{{ url('/contact?service=transports') }}" class="text-cyan-neon underline">Contactez le service Mobilité</a>.</p>
</x-public.panel>
@endsection
