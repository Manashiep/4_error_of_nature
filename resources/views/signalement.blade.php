@extends('layouts.public', ['title' => 'Signaler un problème'])

@section('content')
<x-public.breadcrumb :items="[['Services', '/services'], ['Signaler un problème']]" />
<x-public.page-hero title="Signaler un problème" lead="Un lampadaire cassé, une fuite, un déchet abandonné : dites-nous ce qui s'est passé et où, nous transmettons au bon service." />

<x-public.panel class="mx-auto my-[1.1rem] max-w-[40rem] p-5" aria-labelledby="h-s">
    <h2 id="h-s" class="mb-2 font-hud text-[1.125rem] font-bold tracking-[.05em]">Votre signalement</h2>

    {{-- D16 : confirmation immédiate --}}
    @if (session('sent'))
        <div id="confirmation" role="alert" class="mb-4 rounded-xl border-2 border-ok p-4 hc:border-white">
            <p class="font-bold text-ok hc:text-white">✔ Signalement enregistré.</p>
            <p>Il a bien été pris en compte et transmis aux services. Numéro de suivi : <b>{{ session('sent') }}</b>.
               Vous pouvez suivre son état dans <a href="{{ route('dashboard') }}" class="text-cyan-neon underline">votre espace</a>. Inutile de le renvoyer.</p>
        </div>
    @endif
    @if ($errors->any())
        <p role="alert" class="mb-2 rounded-xl border-2 border-[#ff8aa3] p-3 hc:border-white">Le signalement n'a pas été envoyé : corrigez les champs signalés ci-dessous.</p>
    @endif

    <form method="POST" action="{{ route('signalement.store') }}" novalidate>
        @csrf
        <x-public.field name="category" label="Type de problème" type="select" required>
            <option value="">Choisir…</option>
            @foreach ($categories as $c)
                <option value="{{ $c }}" @selected(old('category') === $c)>{{ $c }}</option>
            @endforeach
        </x-public.field>
        <x-public.field name="description" label="Que s'est-il passé ?" type="textarea" required help="Ex. : le lampadaire est éteint depuis trois jours, la rue est très sombre." />
        <x-public.field name="location" label="Où ?" required autocomplete="street-address" help="Adresse, rue ou repère proche (ex. : rue des Jardins, devant l'école)." />
        <x-public.button class="mt-4 w-full text-center">Envoyer le signalement</x-public.button>
    </form>
</x-public.panel>
@endsection
