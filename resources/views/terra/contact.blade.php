{{-- D04 — Contact. Le formulaire est le composant Livewire App\Livewire\ContactForm --}}
<x-layouts.terra title="Contact">
  <section class="hero hud" aria-labelledby="titre"><h1 id="titre">Contacter les services municipaux</h1><p>Posez votre question ou décrivez votre difficulté. Vous recevrez une confirmation d'envoi.</p></section>
  <div class="contact-grid">
    <livewire:contact-form />
    <aside class="pane hud" aria-labelledby="h-i"><h2 id="h-i">Autres moyens</h2>
      <ul class="info"><li><b>Mairie de Terra Nova</b>Place du Dôme, secteur Centre</li><li><b>Horaires</b>Du lundi au vendredi, 8 h – 18 h</li><li><b>Urgence</b>Pour un incident grave, appelez le 112.</li></ul>
    </aside>
  </div>
</x-layouts.terra>
