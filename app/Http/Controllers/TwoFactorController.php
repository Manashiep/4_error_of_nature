<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
   public function index()
{
    // On vérifie l'existence de l'ID temporaire de session, et PAS l'authentification Laravel classique
    if (!session()->has('2fa:user_id')) {
        return redirect()->route('login')->withErrors(['email' => 'Session 2FA expirée ou invalide.']);
    }

    return view('auth.two-factor');
}
    public function verify(Request $request)
{
    $request->validate([
        'two_factor_code' => 'required|string',
    ]);

    $userId = session('2fa:user_id');
    if (!$userId) {
        return redirect()->route('login')->withErrors(['email' => 'Session expirée, veuillez vous reconnecter.']);
    }

    $user = User::find($userId);

    if (!$user || !$user->two_factor_code || !$user->two_factor_expires_at) {
        return back()->withErrors(['two_factor_code' => 'Aucun code actif trouvé. Veuillez en demander un nouveau.']);
    }

    // Vérification de l'expiration
    if (now()->gt($user->two_factor_expires_at)) {
        return back()->withErrors(['two_factor_code' => 'Ce code a expiré. Veuillez en générer un nouveau.']);
    }

    // Comparaison stricte en convertissant en string (évite les bugs int vs string)
    if (trim((string) $request->two_factor_code) === trim((string) $user->two_factor_code)) {
        // 1. Nettoyage du code en base
        $user->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        // 2. Suppression de la session temporaire
        session()->forget('2fa:user_id');

        // 3. Connexion officielle
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard')->with('success', 'Authentification réussie !');
    }

    return back()->withErrors(['two_factor_code' => 'Code incorrect.']);
}

    public function resend()
    {
        $userId = session('2fa:user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        $code = rand(100000, 999999);

        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw("Votre code de vérification Nova Terra est : {$code}. Il est valable 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Code de vérification - Nova Terra');
        });

        return back()->with('message', 'Un nouveau code vous a été envoyé par e-mail.');
    }
}
