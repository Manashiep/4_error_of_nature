@props(['title' => 'Portail des habitants'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>{{ $title }} – Terra Nova</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>try{var r=document.documentElement;r.style.setProperty('--fs',localStorage.getItem('tn-fs')||1);if(localStorage.getItem('tn-hc')==='1')r.classList.add('hc')}catch(e){}</script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body>
  <a class="skip" href="#contenu">Aller au contenu</a>
  <x-terra.scene />
  <div class="wrap">
    <x-terra.a11y-bar />
    <header class="top hud">
      <a class="brand" href="{{ route('home') }}" aria-label="Terra Nova, accueil"><span class="logo" aria-hidden="true">&lt;/&gt;</span><span><b>TERRA NOVA</b><small>Portail des habitants</small></span></a>
      <nav aria-label="Navigation principale"><ul class="nav">
        @foreach (['home'=>'Accueil','services'=>'Services','actualites'=>'Actualités','contact'=>'Contact'] as $r => $label)
          <li><a href="{{ route($r) }}" @if(request()->routeIs($r)) aria-current="page" @endif>{{ $label }}</a></li>
        @endforeach
        @auth
          <li><a href="{{ route('espace') }}" @if(request()->routeIs('espace')) aria-current="page" @endif>Mon espace</a></li>
          <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="btn alt" style="padding:.3rem .9rem" type="submit">Déconnexion</button></form></li>
        @else
          <li><a href="{{ route('login') }}">Connexion</a></li><li><a href="{{ route('register') }}">Inscription</a></li>
        @endauth
      </ul></nav>
    </header>
    <main id="contenu" tabindex="-1">{{ $slot }}</main>
    <footer>Terra Nova · Portail officiel des habitants · <a href="{{ route('contact') }}">Contact</a></footer>
  </div>
  @livewireScripts
</body>
</html>
