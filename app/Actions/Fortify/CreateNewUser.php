<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validation et création d'un habitant (D01).
     * Le rôle n'est JAMAIS lu dans le formulaire : tout nouveau compte est « citizen ».
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:120'],
            'firstname' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            // Facultatif à l'inscription : peut être complété plus tard dans le profil
            'terrarian_chip_number' => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z0-9\- ]+$/', Rule::unique(User::class)],
            'password' => ['required', 'string', Password::default(), 'confirmed'],
        ], [
            'required' => 'Le champ « :attribute » est obligatoire.',
            'email' => 'Saisissez une adresse e-mail valide.',
            'email.unique' => 'Un compte existe déjà avec cette adresse e-mail.',
            'terrarian_chip_number.unique' => 'Ce numéro de puce est déjà associé à un compte.',
            'terrarian_chip_number.regex' => 'Utilisez uniquement des lettres, des chiffres, des espaces et des tirets.',
            'max.string' => 'Le champ « :attribute » ne doit pas dépasser :max caractères.',
            'confirmed' => 'Les deux mots de passe ne sont pas identiques.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
        ], [
            'name' => 'nom',
            'firstname' => 'prénom',
            'email' => 'adresse e-mail',
            'terrarian_chip_number' => 'numéro de puce terrarienne',
            'password' => 'mot de passe',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'firstname' => $input['firstname'],
            'email' => $input['email'],
            'terrarian_chip_number' => filled($input['terrarian_chip_number'] ?? null) ? trim($input['terrarian_chip_number']) : null,
            'password' => $input['password'],
            'role' => 'citizen',
        ]);
    }
}
