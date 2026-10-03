{{-- D07 — Accueil. BACK : fournir $announcements (voir app/Support/TerraDemo.php pour le format) --}}
<x-layouts.terra title="Accueil">
  <section class="hero-home hud" aria-labelledby="titre">
    <span class="where">Portail officiel de la ville de Terra Nova</span>
    <h1 id="titre">Tous les services de Terra Nova, au même endroit</h1>
    <p class="lead">Signalez un problème, trouvez le bon service, lisez les annonces de la ville ou écrivez à la mairie, sans vous déplacer.</p>
    <div class="cta-row">
      <a class="btn" href="{{ route('services') }}">Voir les services</a>
      @auth <a class="btn alt" href="{{ route('espace') }}">Mon espace</a>
      @else <a class="btn alt" href="{{ route('register') }}">Créer mon compte</a><a class="btn alt" href="{{ route('login') }}">Me connecter</a> @endauth
    </div>
  </section>

  <section class="sec hud pane" aria-labelledby="h-srv">
    <h2 id="h-srv">Que souhaitez-vous faire ? <a href="{{ route('services') }}">Tous les services</a></h2>
    <ul class="tiles">
      @foreach ([
        ['🚨','Signaler un problème','Panne, fuite, éclairage, voirie.','Signaler',route('contact')],
        ['📄','Mes démarches','État civil, attestations, dossiers.','Commencer',route('services').'#demarches'],
        ['🚆','Transports','Lignes, horaires, perturbations.','Consulter',route('services').'#transports'],
        ['📰','Annonces de la ville','Infos pratiques et changements de service.','Lire',route('actualites')],
        ['✉️','Contacter la mairie','Une question ? Écrivez aux services.','Écrire',route('contact')],
        ['👤','Mon espace personnel','Retrouvez vos informations et démarches.','Accéder',auth()->check() ? route('espace') : route('login')],
      ] as [$ico,$t,$d,$cta,$url])
        <li><a class="tile" href="{{ $url }}"><span class="ico" aria-hidden="true">{{ $ico }}</span><b>{{ $t }}</b><span class="d">{{ $d }}</span><span class="go-l">{{ $cta }}</span></a></li>
      @endforeach
    </ul>
  </section>

  <div class="grid sec">
    <section class="pane hud" aria-labelledby="h-news">
      <h2 id="h-news">Dernières annonces <a href="{{ route('actualites') }}">Toutes les annonces</a></h2>
      <ul class="news">
        @forelse (collect($announcements)->take(3) as $a)
          <li><time datetime="{{ \Illuminate\Support\Carbon::parse($a['date'])->toDateString() }}">{{ \Illuminate\Support\Carbon::parse($a['date'])->translatedFormat('j M') }}</time>
            <div><h3>{{ $a['title'] }}</h3><p>{{ $a['excerpt'] }}</p></div></li>
        @empty
          <li class="empty" style="display:block">Aucune annonce pour le moment.</li>
        @endforelse
      </ul>
    </section>
    <aside class="pane hud" aria-labelledby="h-how">
      <h2 id="h-how">Pour démarrer</h2>
      <ol class="info" style="padding-left:1.2rem">
        <li><b>1. Créez votre compte</b>Quelques informations suffisent.</li>
        <li><b>2. Accédez à votre espace</b>Vos démarches y sont réunies.</li>
        <li><b>3. Suivez vos demandes</b>Chaque message reçoit une confirmation.</li>
      </ol>
      @guest <a class="btn" href="{{ route('register') }}" style="margin-top:1rem">Créer mon compte</a> @endguest
    </aside>
  </div>
</x-layouts.terra>
