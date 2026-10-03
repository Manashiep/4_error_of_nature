{{-- D03 — Espace citoyen (auth). BACK : fournir $requests (ref, subject, status[nouveau|en-cours|traite], date) --}}
@php($labels = ['nouveau'=>'Reçue','en-cours'=>'En cours','traite'=>'Traitée'])
<x-layouts.terra title="Mon espace">
  <section class="hero hud" aria-labelledby="titre">
    <h1 id="titre">Bonjour {{ auth()->user()->name }}</h1>
    <p>Voici votre espace personnel : vos demandes et les accès rapides aux services.</p>
    <div class="cta-row"><a class="btn" href="{{ route('contact') }}">Nouvelle demande</a><a class="btn alt" href="{{ route('services') }}">Voir les services</a></div>
  </section>
  <div class="grid sec">
    <section class="pane hud" aria-labelledby="h-r">
      <h2 id="h-r">Mes demandes</h2>
      <ul class="news">
        @forelse ($requests as $r)
          <li><time datetime="{{ \Illuminate\Support\Carbon::parse($r['date'])->toDateString() }}">{{ \Illuminate\Support\Carbon::parse($r['date'])->translatedFormat('j M') }}</time>
            <div><span class="st {{ $r['status'] }}">{{ $labels[$r['status']] ?? 'Reçue' }}</span><h3>{{ $r['subject'] }}</h3><p>Référence {{ $r['ref'] }}</p></div></li>
        @empty
          <li class="empty" style="display:block">Vous n'avez pas encore de demande. <a href="{{ route('contact') }}">Écrire à la mairie</a></li>
        @endforelse
      </ul>
    </section>
    <aside class="pane hud" aria-labelledby="h-q"><h2 id="h-q">Accès rapides</h2>
      <ul class="info"><li><a href="{{ route('services') }}">Services de la ville</a></li><li><a href="{{ route('actualites') }}">Annonces</a></li><li><a href="{{ route('contact') }}">Contacter la mairie</a></li></ul>
    </aside>
  </div>
</x-layouts.terra>
