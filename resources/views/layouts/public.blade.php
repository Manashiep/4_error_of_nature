@php
    // Bandeau d'alerte (D18 / F29 / F30 / F31) : scope Announcement::banner() (alertes d'abord)
    $alerts ??= \App\Models\Announcement::banner()->take(3)->get();
@endphp
<!DOCTYPE html>
<html lang="{{ session('lang', 'fr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ?? 'Accueil' }} – Terra Nova</title>
    <meta name="description" content="@yield('description', 'Portail officiel des habitants de Nova Terra : services municipaux, annonces de la ville et contact avec la mairie.')">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;500&family=Manrope:wght@400;500;700&display=swap" rel="stylesheet">
    <script>try{var r=document.documentElement;r.style.setProperty('--fs',localStorage.getItem('tn-fs')||1);if(localStorage.getItem('tn-hc')==='1')r.classList.add('hc')}catch(e){}</script>
    @vite(['resources/css/public.css', 'resources/js/public.js'])
</head>
<body>
    <a href="#contenu" class="absolute -left-[999px] top-2 z-50 rounded-full bg-cyan px-4 py-2 font-bold text-on-cyan focus:left-2">Aller au contenu</a>

    <div aria-hidden="true" class="hc:hidden"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>

    <div class="relative z-10 mx-auto max-w-[1120px] px-4 pb-16 pt-2 sm:px-6">
        @include('partials.public.a11y-bar')
        @include('partials.public.header')
        <x-public.alerts :alerts="$alerts" />
        <main id="contenu" tabindex="-1" class="focus:outline-none">
            @yield('content')
        </main>
        @include('partials.public.footer')
    </div>
</body>
</html>
