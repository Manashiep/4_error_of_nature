{{-- D01 — Inscription. Poste vers la route Fortify `register` (champs : name, email, password, password_confirmation) --}}
<x-layouts.terra title="Inscription">
  <section class="pane hud narrow" aria-labelledby="titre">
    <h1 id="titre" style="font-size:1.6rem">Créer mon compte</h1><p class="help" style="margin-bottom:.5rem">Un compte vous donne accès à votre espace personnel.</p>
    <form method="POST" action="{{ route('register') }}" novalidate>
      @csrf
      @foreach ([['name','Nom complet','text','name'],['email','Adresse e-mail','email','email'],['password','Mot de passe','password','new-password'],['password_confirmation','Confirmer le mot de passe','password','new-password']] as [$f,$l,$t,$ac])
        <label for="r-{{ $f }}">{{ $l }}</label>
        <input id="r-{{ $f }}" name="{{ $f }}" type="{{ $t }}" autocomplete="{{ $ac }}" required @if($t!=='password') value="{{ old($f) }}" @endif @error($f) aria-invalid="true" aria-describedby="e-{{ $f }}" @enderror>
        @if($f==='password')<p class="help">8 caractères minimum.</p>@endif
        @error($f)<p class="msg err" id="e-{{ $f }}">{{ $message }}</p>@enderror
      @endforeach
      <button class="go" type="submit">Créer mon compte</button>
    </form>
    <p class="alt-l">Déjà inscrit ? <a href="{{ route('login') }}">Me connecter</a></p>
  </section>
</x-layouts.terra>
