@extends('layouts.public', ['title' => 'Vérification de sécurité'])

@section('content')
<div class="my-12 flex justify-center">
    <div class="w-full max-w-md p-7 rounded-2xl border border-edge/40 bg-glass shadow-lg">
        <h1 class="text-xl font-medium text-ink">Vérification de sécurité</h1>

        <p class="mt-2 text-sm text-mute leading-relaxed">
            Pour sécuriser votre compte, veuillez saisir le code de vérification ci-dessous.
        </p>

        {{-- Affichage dynamique direct du code stocké en base pour l'utilisateur connecté --}}
        @if (auth()->check() && auth()->user()->two_factor_code)
            <div class="mt-4 rounded-xl border border-amber-500/50 bg-amber-500/10 p-4 text-sm text-amber-800">
                <span class="font-bold">🧪 Mode Local Dynamique :</span> Votre code actuel est :
                <strong class="text-lg font-mono tracking-widest bg-amber-200 px-2 py-0.5 rounded ml-1">{{ auth()->user()->two_factor_code }}</strong>
            </div>
        @endif

        @if (session('message'))
            <div class="mt-4 rounded-xl border border-emerald-500/50 bg-emerald-500/10 p-3 text-xs text-emerald-600">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('2fa.verify') }}" class="mt-6 grid gap-4">
            @csrf

            <div>
                <label for="two_factor_code" class="block text-xs font-bold uppercase tracking-wider text-cyan-600">
                    Code de vérification
                </label>
                <input type="text"
                       name="two_factor_code"
                       id="two_factor_code"
                       maxlength="6"
                       required
                       autofocus
                       placeholder="123456"
                       class="mt-1 w-full rounded-xl border border-gray-300 bg-white p-3 text-center text-xl font-mono tracking-widest text-gray-900 focus:border-cyan-500 focus:outline-none">

                @error('two_factor_code')
                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full mt-2 rounded-xl bg-cyan-600 p-3 text-white font-medium hover:bg-cyan-700 transition">
                Valider et accéder à mon espace
            </button>
        </form>

        <div class="mt-6 border-t border-gray-200 pt-4 text-center">
            <a href="{{ route('2fa.resend') }}" class="text-xs text-cyan-600 hover:underline font-medium">
                🔄 Générer un nouveau code
            </a>
        </div>
    </div>
</div>
@endsection
