@extends('layouts.public', ['title' => $service->tr('name')])

@section('description', $service->tr('short_description') ?: $service->tr('name'))

@section('content')
<x-public.breadcrumb :items="[['Services', '/services'], [$service->tr('name')]]" />

<div class="my-4 grid items-start gap-6 md:grid-cols-[minmax(0,1fr)_20rem]">
    <div class="grid gap-6">
        <x-public.panel as="article" class="p-7" aria-labelledby="titre">
            <p class="flex flex-wrap gap-2 text-[.8rem]">
                <span class="rounded-full border border-cyan/60 px-3 py-0.5 text-cyan">{{ $service->category }}</span>
                @if ($service->is_featured)
                    <span class="rounded-full border border-violet/70 px-3 py-0.5 text-violet">Service prioritaire</span>
                @endif
            </p>
            <h1 id="titre" class="mt-3 font-hud text-[clamp(1.5rem,4vw,2.5rem)] font-light leading-[1.1] tracking-tight">{{ $service->tr('name') }}</h1>
            @if ($service->tr('short_description'))
                <p class="mt-4 text-[1.15rem] text-mute">{{ $service->tr('short_description') }}</p>
            @endif

            {{-- F38 : indisponibilité visible avant de commencer la démarche, avec quoi faire à la place --}}
            @unless ($service->is_active)
                <div role="status" class="mt-5 rounded-2xl border-2 border-warn bg-warn/15 p-4 hc:border-white hc:bg-black">
                    <h2 class="font-hud text-[.95rem] font-medium">Service momentanément indisponible</h2>
                    <p class="mt-1">Ce service n'accepte pas de démarche pour le moment.
                        @if ($service->contact_email || $service->contact_phone)
                            En attendant, contactez-le directement
                            @if ($service->contact_phone) par téléphone au <a class="text-cyan underline" href="tel:{{ preg_replace('/[^+\d]/', '', $service->contact_phone) }}">{{ $service->contact_phone }}</a>@endif
                            @if ($service->contact_phone && $service->contact_email) ou @endif
                            @if ($service->contact_email) par e-mail à <a class="text-cyan underline" href="mailto:{{ $service->contact_email }}">{{ $service->contact_email }}</a>@endif.
                        @else
                            Écrivez-nous via le formulaire de contact, nous vous répondrons dès la reprise.
                        @endif
                    </p>
                </div>
            @endunless

            @if ($service->image_url)
                <img src="{{ $service->image_url }}" alt="" class="mt-6 max-h-72 w-full rounded-3xl object-cover">
            @endif

            <div class="mt-6 grid max-w-[46rem] gap-4 text-[1.05rem] leading-relaxed">
                @foreach (preg_split("/\n{2,}/", trim((string) $service->tr('description'))) as $para)
                    @if ($para !== '') <p>{!! nl2br(e($para)) !!}</p> @endif
                @endforeach
            </div>

            <p class="mt-7"><a href="{{ route('services.index') }}" class="text-cyan hover:underline hc:underline">← Tous les services</a></p>
        </x-public.panel>
    </div>

    <x-public.panel as="aside" class="p-6" aria-labelledby="h-c">
        <h2 id="h-c" class="mb-4 font-hud text-[1rem] font-medium">Contacter ce service</h2>
        <ul class="grid gap-3 text-[.98rem]">
            @if ($service->contact_phone)
                <li><b class="block text-cyan">Téléphone</b><a class="text-ink hover:underline" href="tel:{{ preg_replace('/[^+\d]/', '', $service->contact_phone) }}">{{ $service->contact_phone }}</a></li>
            @endif
            @if ($service->contact_email)
                <li><b class="block text-cyan">E-mail</b><a class="break-all text-ink hover:underline" href="mailto:{{ $service->contact_email }}">{{ $service->contact_email }}</a></li>
            @endif
            <li><b class="block text-cyan">Consultations</b>{{ $service->views_count }} fois</li>
        </ul>

        @if (session('contact_sent'))
            <div id="contact-service" role="status" class="mt-5 rounded-2xl border-2 border-ok bg-ok/10 p-4 hc:border-white hc:bg-black">
                <p class="font-bold text-ok">✔ Message envoyé.</p>
                <p>Votre demande a bien été transmise au service {{ $service->tr('name') }}.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" novalidate class="mt-5 grid gap-4" id="contact-service-form">
            @csrf
            <input type="hidden" name="service" value="{{ $service->slug }}">
            <x-public.field name="name" label="Nom" required autocomplete="name" :value="auth()->user()?->name" />
            <x-public.field name="email" label="Adresse e-mail" type="email" required autocomplete="email" :value="auth()->user()?->email" />
            <x-public.field name="message" label="Votre message" type="textarea" required help="Décrivez précisément votre demande ou votre difficulté." />
            <x-public.button class="w-full">Envoyer un message</x-public.button>
        </form>
    </x-public.panel>
</div>

@if ($related->isNotEmpty())
    <x-public.panel class="my-6 p-6" aria-labelledby="h-rel">
        <h2 id="h-rel" class="mb-5 font-hud text-[1.1rem] font-medium">Dans la même catégorie</h2>
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($related as $s)
                <x-public.service-card :service="$s" />
            @endforeach
        </ul>
    </x-public.panel>
@endif
@endsection
