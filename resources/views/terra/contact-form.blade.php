<section class="pane hud" aria-labelledby="h-f">
  <h2 id="h-f">Votre message</h2>
  @if ($sent)
    <div class="msg ok" role="status" tabindex="-1" x-init="$el.focus()">Message envoyé. Les services municipaux vous répondront dès que possible.</div>
    <button type="button" class="btn alt" style="margin-top:1rem" wire:click="$set('sent', false)">Envoyer un autre message</button>
  @else
    <form wire:submit="send" novalidate>
      <label for="c-n">Nom</label>
      <input id="c-n" wire:model="name" autocomplete="name" required @error('name') aria-invalid="true" aria-describedby="e-n" @enderror>
      @error('name')<p class="msg err" id="e-n">{{ $message }}</p>@enderror

      <label for="c-e">Adresse e-mail</label>
      <input id="c-e" type="email" wire:model="email" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="e-e" @enderror>
      @error('email')<p class="msg err" id="e-e">{{ $message }}</p>@enderror

      <label for="c-s">Service concerné</label>
      <select id="c-s" wire:model="service">
        @foreach (['Je ne sais pas','Signalement','Démarches','Transports','Logement','Santé'] as $o)<option>{{ $o }}</option>@endforeach
      </select>

      <label for="c-m">Message</label>
      <textarea id="c-m" rows="5" wire:model="message" required @error('message') aria-invalid="true" aria-describedby="e-m" @enderror></textarea>
      @error('message')<p class="msg err" id="e-m">{{ $message }}</p>@enderror

      <button class="go" type="submit" wire:loading.attr="disabled">
        <span wire:loading.remove wire:target="send">Envoyer le message</span><span wire:loading wire:target="send">Envoi en cours…</span>
      </button>
    </form>
  @endif
</section>
