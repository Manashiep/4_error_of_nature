<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/** F25 : signalement d'un problème. Création et soutien réservés aux habitants connectés ; consultation publique. */
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

    // Public : demandes des habitants, les plus soutenues d'abord
    public function index(Request $request): View
    {
        $cat = $request->query('categorie');
        if ($cat && ! in_array($cat, Report::CATEGORIES, true)) {
            $cat = null;
        }

        return view('demandes.index', [
            'items' => Report::withCount('supports')
                ->when($cat, fn ($b) => $b->where('category', $cat))
                ->orderByRaw("case status when 'traite' then 1 else 0 end")
                ->orderByDesc('supports_count')->latest()
                ->simplePaginate(9)->withQueryString(),
            'cat' => $cat,
            'categories' => Report::CATEGORIES,
        ]);
    }

    // Public : détail d'une demande + bouton Soutenir
    public function show(Request $request, Report $report): View
    {
        $user = $request->user();

        return view('demandes.show', [
            'report' => $report->loadCount('supports'),
            'mine' => $user && $report->user_id === $user->id,
            'supported' => $user ? $report->supports()->where('user_id', $user->id)->exists() : false,
        ]);
    }

    // Habitant connecté : soutenir une demande existante (une seule fois par habitant)
    public function support(Request $request, Report $report): RedirectResponse
    {
        $to = route('demandes.show', $report).'#soutien';

        if ($report->status === 'traite') {
            return redirect($to)->with('info', 'Cette demande est déjà traitée : elle ne peut plus être soutenue.');
        }
        if ($report->user_id === $request->user()->id) {
            return redirect($to)->with('info', 'Vous êtes à l\'origine de cette demande : elle compte déjà.');
        }

        $support = $report->supports()->firstOrCreate(['user_id' => $request->user()->id]);
        $n = $report->supports()->count();

        return redirect($to)->with(
            $support->wasRecentlyCreated ? 'supported' : 'info',
            $support->wasRecentlyCreated
                ? "Votre soutien a bien été pris en compte. Cette demande compte maintenant {$n} soutien".($n > 1 ? 's' : '').'.'
                : 'Vous soutenez déjà cette demande.'
        );
    }
}
