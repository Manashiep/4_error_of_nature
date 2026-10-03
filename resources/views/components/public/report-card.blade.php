{{-- Carte d'une demande d'habitant (réclame ->withCount('supports')) --}}
@props(['report'])
@php
    $r = $report;
    $tone = match ($r->status) { 'traite' => 'border-ok/70 text-ok', 'en_cours' => 'border-violet/70 text-violet', default => 'border-cyan/60 text-cyan' };
    $n = $r->supports_count ?? 0;
@endphp
<li>
    <a href="{{ route('demandes.show', $r) }}"
       class="flex h-full flex-col gap-2 rounded-3xl border border-edge bg-glass p-5 text-ink no-underline transition hover:-translate-y-1 hover:bg-glass-hi hc:border-2 hc:border-white hc:bg-black">
        <span class="flex flex-wrap gap-2 text-[.78rem]">
            <span class="rounded-full border border-edge px-3 py-0.5">{{ $r->category }}</span>
            <span class="rounded-full border px-3 py-0.5 {{ $tone }}">{{ $r->status_label }}</span>
        </span>
        <b class="line-clamp-3 font-bold leading-snug">{{ \Illuminate\Support\Str::limit($r->description, 110) }}</b>
        <span class="text-[.9rem] text-mute">{{ $r->location }}</span>
        <span class="mt-auto pt-1 font-bold text-cyan">{{ $n }} soutien{{ $n > 1 ? 's' : '' }} · Voir la demande →</span>
    </a>
</li>
