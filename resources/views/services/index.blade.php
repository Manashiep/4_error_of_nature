@extends('layouts.public', ['title' => 'Services'])

@section('content')
<x-public.breadcrumb :items="[['Services']]" />
<x-public.page-hero title="Services de la ville" lead="Choisissez la catégorie qui correspond à votre besoin, puis ouvrez le service pour voir comment faire." />

<x-public.panel class="my-[1.1rem] p-5" aria-labelledby="h-l">
    <h2 id="h-l" class="mb-[.9rem] font-hud text-[1.125rem] font-bold tracking-[.05em]">Tous les services</h2>

    {{-- F32 : recherche (fonctionne sans JavaScript) --}}
    <form role="search" method="get" action="{{ url('/services') }}" class="mb-4 flex flex-wrap gap-[.6rem]">
        <label class="sr-only" for="svc-q">Rechercher un service</label>
        <input id="svc-q" type="search" name="q" value="{{ $q }}" autocomplete="off"
               placeholder="Rechercher un service (ex. santé, lampadaire, transport)"
               class="min-w-[12rem] flex-1 rounded-[.55rem] border border-[rgba(120,200,255,.45)] bg-white/5 px-3 py-[.6rem] text-ink placeholder:text-[#7f97c4] hc:border-2 hc:border-white hc:bg-black">
        @if ($cat !== 'all') <input type="hidden" name="cat" value="{{ $cat }}"> @endif
        <x-public.button>Rechercher</x-public.button>
    </form>

    <nav aria-label="Catégories">
        <ul class="mb-4 flex flex-wrap gap-2">
            @foreach (['all' => 'Tous'] + $categories as $key => $label)
                @php $href = url('/services').'?'.http_build_query(array_filter(['q' => $q, 'cat' => $key === 'all' ? null : $key])); @endphp
                <li>
                    <a href="{{ $href }}" @if($cat === $key) aria-current="true" @endif
                       class="block rounded-full border border-white/35 px-[.9rem] py-[.3rem] text-white no-underline hover:border-cyan-neon aria-[current=true]:border-pink-neon aria-[current=true]:bg-pink-neon/10 aria-[current=true]:shadow-[0_0_12px_#ff2d8a55] hc:border-2 hc:aria-[current=true]:bg-[#ffe600] hc:aria-[current=true]:text-black">{{ $label }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    <p role="status" aria-live="polite" class="mb-3 text-[.9rem] text-mute">
        @if ($services->isEmpty())
            Aucun service ne correspond.
        @else
            {{ $services->count() }} {{ $services->count() > 1 ? 'services' : 'service' }}@if ($q !== '') pour « {{ $q }} »@endif
        @endif
    </p>

    @if ($services->isEmpty())
        <p class="rounded-xl border border-dashed border-line p-7 text-center text-mute">
            Essayez un autre mot ou choisissez « Tous ». <a href="{{ url('/services') }}" class="text-cyan-neon underline">Voir tous les services</a>
        </p>
    @else
        <ul class="grid grid-cols-[repeat(auto-fill,minmax(15rem,1fr))] gap-3">
            @foreach ($services as $s)
                <li id="{{ $s['slug'] }}" class="flex scroll-mt-4 flex-col gap-1 rounded-xl border border-line bg-white/5 p-[.9rem] target:border-cyan-neon target:shadow-[0_0_18px_rgba(56,232,255,.3)] hc:border-2 hc:border-white hc:bg-black">
                    <h3 class="text-[1.2rem] font-semibold"><span aria-hidden="true">{{ $s['ico'] }}</span> {{ $s['title'] }}</h3>
                    <p class="text-mute">{{ $s['desc'] }}</p>
                    <p class="flex flex-wrap gap-2">
                        <span class="rounded-full border border-cyan-neon/50 px-[.65rem] py-[.1rem] text-[.8rem] text-cyan-neon">{{ $categories[$s['cat']] }}</span>
                        @if ($s['featured'])
                            <span class="rounded-full border border-[#ffc23d]/60 px-[.65rem] py-[.1rem] text-[.8rem] text-[#ffc23d] hc:border-white hc:text-white">Les plus utilisés</span>
                        @endif
                    </p>
                    <p class="text-[.85rem] text-mute">Horaires : {{ $s['hours'] }}</p>
                    <a href="{{ url($s['href']) }}" class="mt-auto pt-2 font-bold text-cyan-neon hover:underline hc:underline">{{ $s['cta'] }}<span class="sr-only"> : {{ $s['title'] }}</span></a>
                </li>
            @endforeach
        </ul>
    @endif
</x-public.panel>
@endsection
