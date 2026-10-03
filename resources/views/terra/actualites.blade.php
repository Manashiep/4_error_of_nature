{{-- D06 — Annonces. BACK : fournir $announcements (date, title, excerpt, tag, important). --}}
<x-layouts.terra title="Annonces">
  <section class="hero hud" aria-labelledby="titre"><h1 id="titre">Annonces de la ville</h1><p>Annonces municipales, changements de service et informations pratiques.</p></section>
  <section class="pane hud" aria-labelledby="h-a">
    <h2 id="h-a">Publications récentes</h2>
    <ul class="news">
      @forelse ($announcements as $a)
        @php($d = \Illuminate\Support\Carbon::parse($a['date']))
        <li><time datetime="{{ $d->toDateString() }}">{{ $d->translatedFormat('j M') }}</time>
          <div><span class="tag {{ !empty($a['important']) ? 'warn' : '' }}">{{ !empty($a['important']) ? 'Important' : $a['tag'] }}</span><h3>{{ $a['title'] }}</h3><p>{{ $a['excerpt'] }}</p></div></li>
      @empty
        <li class="empty" style="display:block">Aucune annonce pour le moment. Revenez bientôt.</li>
      @endforelse
    </ul>
  </section>
</x-layouts.terra>
