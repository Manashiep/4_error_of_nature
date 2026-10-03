<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(Request $request): View
    {
        $services = Service::orderBy('name')->get();
        $selected = $services->pluck('slug')->contains($request->query('service')) ? $request->query('service') : null;

        return view('contact', compact('services', 'selected'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'service' => ['nullable', 'exists:services,slug'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'required' => 'Le champ « :attribute » est obligatoire.',
            'email' => 'Saisissez une adresse e-mail valide.',
            'min.string' => 'Le champ « :attribute » doit contenir au moins :min caractères.',
            'max.string' => 'Le champ « :attribute » ne doit pas dépasser :max caractères.',
            'exists' => 'Choisissez un service dans la liste.',
        ], [
            'name' => 'nom', 'email' => 'adresse e-mail', 'service' => 'service concerné', 'message' => 'message',
        ]);

        $reference = 'MSG-'.strtoupper(Str::random(6));

        ContactMessage::create($data + [
            'reference' => $reference,
            'user_id' => $request->user()?->id,
        ]);

        return redirect(route('contact').'#confirmation')->with('sent', $reference);
    }
}
