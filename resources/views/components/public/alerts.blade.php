{{-- D18 / F29 / F30 / F31 : bandeau sur toutes les pages publiques, avec "Que faire" --}}
@props(['alerts' => collect()])
@foreach ($alerts as $a)
    @php
        $isAlert = $a->level === 'alerte';
        $tone = $isAlert ? 'border-coral bg-coral/20' : ($a->level === 'important' ? 'border-warn bg-warn/15' : 'border-cyan bg-cyan/10');
    @endphp
    <section role="{{ $isAlert ? 'alert' : 'status' }}" aria-labelledby="al-{{ $a->id }}"
             class="my-3 rounded-[28px] border-2 p-4 backdrop-blur-[26px] {{ $tone }} hc:border-white hc:bg-black">
        <div class="flex flex-wrap items-start gap-3">
            <div class="min-w-0 flex-1">
                <h2 id="al-{{ $a->id }}" class="font-hud text-[.95rem] font-medium">
                    <span class="font-bold">{{ $isAlert ? 'Alerte' : ($a->level === 'important' ? 'Important' : 'Information') }} ·</span> {{ $a->title }}
                </h2>
                <p class="mt-1">{{ $a->summary }}</p>
                @if ($a->action)
                    <p class="mt-2 font-bold">Que faire : <span class="font-medium">{{ $a->action }}</span></p>
                @endif
            </div>
            <a href="{{ route('annonces.show', $a->slug) }}" class="rounded-full border border-ink/50 px-4 py-2 font-medium text-ink no-underline hover:bg-ink/10 hc:border-2 hc:underline">Voir le détail<span class="sr-only"> : {{ $a->title }}</span></a>
        </div>
    </section>
@endforeach
