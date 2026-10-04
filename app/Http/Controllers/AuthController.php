<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 1. On cherche l'utilisateur par son e-mail
        $user = User::where('email', $credentials['email'])->first();

        // 2. On vérifie si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
            ])->onlyInput('email');
        }

        // 3. IDENTIFIANTS OK : Mais on NE CONNECTE PAS l'utilisateur tout de suite.
        // On génère le code 2FA
        $code = rand(100000, 999999);

        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        // Envoi du vrai e-mail (intercepté par Mailpit en local)
        Mail::raw("Votre code de vérification Nova Terra est : {$code}. Il est valable 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Code de vérification - Nova Terra');
        });

        // 4. On stocke l'ID de l'utilisateur temporairement en session pour l'étape du 2FA
        session(['2fa:user_id' => $user->id]);

        // 5. On redirige vers la page du code (l'utilisateur N'EST PAS ENCORE CONNECTÉ)
        return redirect()->route('2fa.index');
    }
}
