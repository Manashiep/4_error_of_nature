{{-- Carte d'un service (données 100 % issues de la table services - Optimisée Éco-conception E01) --}}
@props(['service'])
@php
    $s = $service;
    $imgPath = $s->image_path ?? $s->image_url;
@endphp

<li>
    <a href="{{ route('services.show', $s->slug) }}"
       class="flex h-full flex-col gap-3 rounded-3xl border border-edge bg-glass p-5 text-ink no-underline transition hover:-translate-y-1 hover:bg-glass-hi hc:border-2 hc:border-white hc:bg-black">

        {{-- Affichage éco-conçu de l'image du service --}}
        @if ($imgPath)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($imgPath) }}"
                 alt="{{ $s->tr('name') }}"
                 loading="lazy"
                 decoding="async"
                 width="320"
                 height="128"
                 class="h-32 w-full rounded-2xl object-cover border border-edge/30">
        @endif

        <span class="flex flex-wrap gap-2 text-[.78rem]">
            <span class="rounded-full border border-cyan/60 px-3 py-0.5 text-cyan">{{ $s->category }}</span>
            @if ($s->is_featured)
                <span class="rounded-full border border-violet/70 px-3 py-0.5 text-violet">Prioritaire</span>
            @endif
            @unless ($s->is_active)
                <span class="rounded-full border border-warn/70 px-3 py-0.5 text-warn">Indisponible</span>
            @endunless
        </span>

        <b class="font-hud text-[1rem] font-medium leading-snug">{{ $s->tr('name') }}</b>

        @if ($s->tr('short_description'))
            <span class="line-clamp-3 text-mute">{{ \Illuminate\Support\Str::limit($s->tr('short_description'), 140) }}</span>
        @endif

        <span class="mt-auto font-bold text-cyan">Voir le service<span class="sr-only"> : {{ $s->tr('name') }}</span> →</span>
    </a>
</li>
