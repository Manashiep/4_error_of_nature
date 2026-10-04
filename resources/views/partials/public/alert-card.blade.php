@php
    $styles = [
        'alerte'    => 'border-red-500 bg-red-500/15',
        'important' => 'border-amber-400 bg-amber-400/15',
        'info'      => 'border-cyan bg-cyan/10',
    ];
    $labels = ['alerte' => 'Alerte', 'important' => 'Important', 'info' => 'Information'];
    $expired = $a->is_expired;
@endphp

<li class="rounded-2xl border-2 p-4 hc:border-white hc:bg-black {{ $expired ? 'border-edge bg-glass' : ($styles[$a->level] ?? $styles['info']) }}">
    <div class="flex flex-wrap items-center gap-2 text-[.8rem]">
        <span class="rounded-full border border-current px-2.5 py-0.5 font-bold">{{ $labels[$a->level] ?? 'Information' }}</span>
        <span class="text-mute">{{ $a->category }}</span>
        <time datetime="{{ $a->published_at->toIso8601String() }}" class="font-bold text-cyan">{{ $a->published_at->locale('fr')->translatedFormat('j M Y') }}</time>
        @if ($expired)
            <span class="text-mute">· Terminée</span>
        @endif
    </div>
    <h3 class="mt-1 text-[1.1rem] font-bold">
        <a href="{{ route('alerts.show', $a) }}" class="text-ink no-underline hover:text-cyan hover:underline hc:underline">{{ $a->title }}</a>
    </h3>
    @if ($a->summary)
        <p class="mt-1 text-mute">{{ $a->summary }}</p>
    @endif
    <a href="{{ route('alerts.show', $a) }}" class="mt-2 inline-block font-bold text-cyan hover:underline hc:underline">Voir le détail →</a>
</li>