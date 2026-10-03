<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ?? 'Accueil' }} – {{ config('app.name', 'Nova Terra') }}</title>
    <meta name="description" content="@yield('description', 'Portail officiel des habitants de Nova Terra : services municipaux, annonces de la ville et contact avec la mairie.')">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Applique taille du texte + contraste mémorisés avant le premier affichage --}}
    <script>try{var r=document.documentElement;r.style.setProperty('--fs',localStorage.getItem('tn-fs')||1);if(localStorage.getItem('tn-hc')==='1')r.classList.add('hc')}catch(e){}</script>
    @vite(['resources/css/public.css', 'resources/js/public.js'])
</head>
<body>
    <a href="#contenu" class="absolute -left-[999px] top-2 z-50 rounded-lg bg-cyan-neon px-4 py-2 font-bold text-black focus:left-2">Aller au contenu</a>

    {{-- Fond décoratif --}}
    <div id="scene" aria-hidden="true" class="fixed inset-0 -z-20 bg-night hc:hidden [&>svg]:block [&>svg]:size-full"></div>
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 bg-[repeating-linear-gradient(0deg,#ffffff06_0_1px,transparent_1px_4px)] hc:hidden"></div>

    <div class="mx-auto max-w-[1080px] p-4">
        @include('partials.public.a11y-bar')
        @include('partials.public.header')
        <x-public.alerts :alerts="$alerts ?? collect()" />
        <main id="contenu" tabindex="-1" class="focus:outline-none">
            @yield('content')
        </main>
        @include('partials.public.footer')
    </div>
</body>
</html>
