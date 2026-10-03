{{-- D05 — Services. BACK : fournir $services (id, icon, name, desc, cat, cat_label, hours). Filtrage côté navigateur (Alpine). --}}
<x-layouts.terra title="Services">
  <section class="hero hud" aria-labelledby="titre"><h1 id="titre">Services de la ville</h1><p>Choisissez la catégorie qui correspond à votre besoin, puis ouvrez le service pour voir comment faire.</p></section>
  <section class="pane hud" aria-labelledby="h-l"
    x-data="{ items: @js($services), q: '', cat: 'all',
      get cats(){ return [...new Map(this.items.map(i => [i.cat, i.cat_label])).entries()] },
      get shown(){ const t=this.q.trim().toLowerCase(); return this.items.filter(i => (this.cat==='all'||i.cat===this.cat) && (!t || (i.name+' '+i.desc).toLowerCase().includes(t))) } }">
    <h2 id="h-l">Tous les services</h2>
    <div class="tools"><label class="sr-only" for="svc-q">Rechercher un service</label><input id="svc-q" type="search" x-model="q" placeholder="Rechercher un service (ex. transport, eau, santé)"></div>
    <div class="chips" role="group" aria-label="Catégories">
      <button type="button" class="chip" @click="cat='all'" :aria-pressed="(cat==='all').toString()">Tous</button>
      <template x-for="[k,l] in cats" :key="k"><button type="button" class="chip" @click="cat=k" :aria-pressed="(cat===k).toString()" x-text="l"></button></template>
    </div>
    <p class="help" role="status" aria-live="polite" x-text="shown.length ? shown.length + ' service' + (shown.length>1?'s':'') : 'Aucun service ne correspond.'"></p>
    <ul class="cards" style="margin-top:.75rem">
      <template x-for="s in shown" :key="s.id">
        <li class="card" :id="s.id"><h3><span x-text="s.icon" aria-hidden="true"></span> <span x-text="s.name"></span></h3><p x-text="s.desc"></p><span class="tag" x-text="s.cat_label"></span><p class="help" x-text="'Horaires : ' + s.hours"></p></li>
      </template>
    </ul>
    <p class="empty" x-show="!shown.length" x-cloak>Aucun service ne correspond. Essayez un autre mot ou choisissez « Tous ».</p>
  </section>
</x-layouts.terra>
