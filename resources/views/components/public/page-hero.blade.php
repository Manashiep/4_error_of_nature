@props(['title', 'lead' => null])
<x-public.panel class="my-4 px-6 py-8" aria-labelledby="titre">
    <h1 id="titre" class="font-hud text-[clamp(1.4rem,4vw,2.25rem)] font-black leading-[1.15] tracking-[.04em]">{{ $title }}</h1>
    @if ($lead)
        <p class="mt-3 max-w-[40rem] text-[1.15rem] text-mute">{{ $lead }}</p>
    @endif
</x-public.panel>
