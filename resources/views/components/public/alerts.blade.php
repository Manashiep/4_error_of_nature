{{-- D18 / F29 / F31 : bandeau visible sur toutes les pages publiques, avec "Que faire" --}}
@props(['alerts' => collect()])
@foreach ($alerts as $a)
    @php $isAlert = $a->level === 'alerte'; @endphp
    <section role="{{ $isAlert ? 'alert' : 'status' }}" aria-labelledby="al-{{ $a->id }}"
             class="my-3 rounded-2xl border-2 p-4 {{ $isAlert ? 'border-[#ff6b8a] bg-[rgba(90,10,35,.78)]' : 'border-[#ffc23d] bg-[rgba(80,55,0,.72)]' }} hc:border-white hc:bg-black">
        <div class="flex flex-wrap items-start gap-3">
            <span aria-hidden="true" class="text-2xl leading-none">{{ $isAlert ? '🚨' : '⚠️' }}</span>
            <div class="min-w-0 flex-1">
                <h2 id="al-{{ $a->id }}" class="font-hud text-base font-bold tracking-[.04em]">
                    <span class="sr-only">{{ $isAlert ? 'Alerte : ' : 'Information importante : ' }}</span>{{ $a->title }}
                </h2>
                <p class="mt-1">{{ $a->summary }}</p>
                @if ($a->action)
                    <p class="mt-2 font-bold">Que faire : <span class="font-medium">{{ $a->action }}</span></p>
                @endif
            </div>
            <a href="{{ route('annonces.show', $a) }}" class="rounded-lg border border-white/60 px-3 py-1 text-white no-underline hover:bg-white/10 hc:border-2 hc:underline">Voir le détail</a>
        </div>
    </section>
@endforeach
