@props(['title', 'lead' => null])
<x-public.panel class="my-4 px-7 py-9" aria-labelledby="titre">
    <h1 id="titre" class="font-hud text-[clamp(1.6rem,4.5vw,2.8rem)] font-light leading-[1.1] tracking-tight">{{ $title }}</h1>
    @if ($lead)
        <p class="mt-4 max-w-[42rem] text-[1.1rem] text-mute">{{ $lead }}</p>
    @endif
</x-public.panel>
