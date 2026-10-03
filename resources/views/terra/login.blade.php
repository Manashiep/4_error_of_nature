{{-- D03 — Connexion. Poste vers la route Fortify `login` (champs : email, password, remember) --}}
<x-layouts.terra title="Connexion">
  <section class="pane hud narrow" aria-labelledby="titre">
    <h1 id="titre" style="font-size:1.6rem">Connexion</h1><p class="help" style="margin-bottom:.5rem">Retrouvez votre espace personnel et vos démarches.</p>
    @if (session('status'))<p class="msg ok" role="status">{{ session('status') }}</p>@endif
    <form method="POST" action="{{ route('login') }}" novalidate>
      @csrf
      <label for="l-e">Adresse e-mail</label>
      <input id="l-e" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="e-e" @enderror>
      @error('email')<p class="msg err" id="e-e">{{ $message }}</p>@enderror
      <label for="l-p">Mot de passe</label>
      <input id="l-p" name="password" type="password" autocomplete="current-password" required>
      <label style="display:flex;gap:.5rem;align-items:center"><input type="checkbox" name="remember" style="width:auto"> Rester connecté</label>
      <button class="go" type="submit">Se connecter</button>
    </form>
    <p class="alt-l">Pas encore de compte ? <a href="{{ route('register') }}">Créer mon compte</a></p>
  </section>
</x-layouts.terra>
