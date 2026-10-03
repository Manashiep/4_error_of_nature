<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/** F25 : signalement d'un problème (lampadaire, fuite…). Réservé aux habitants connectés. */
class ReportController extends Controller
{
    public function create(): View
    {
        return view('signalement', ['categories' => Report::CATEGORIES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', 'in:'.implode(',', Report::CATEGORIES)],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
        ], [
            'required' => 'Le champ « :attribute » est obligatoire.',
            'min.string' => 'Le champ « :attribute » doit contenir au moins :min caractères.',
            'max.string' => 'Le champ « :attribute » ne doit pas dépasser :max caractères.',
            'in' => 'Choisissez un type de problème dans la liste.',
        ], [
            'category' => 'type de problème', 'description' => 'description', 'location' => 'lieu',
        ]);

        $reference = 'SIG-'.strtoupper(Str::random(6));

        Report::create($data + [
            'reference' => $reference,
            'user_id' => $request->user()->id,
            'status' => 'nouveau',
        ]);

        return redirect(route('signalement').'#confirmation')->with('sent', $reference);
    }
}
