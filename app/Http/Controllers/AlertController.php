<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AlertController extends Controller
{
    /** Toutes les alertes : en cours, puis terminées */
    public function index(): View
    {
        return view('alertes.index', [
            'current' => Alert::current()->urgentFirst()->get(),
            'past' => Alert::past()->latest('published_at')->simplePaginate(10),
        ]);
    }

    /** Détail d'une alerte */
    public function show(Alert $alert): View
    {
        abort_unless($alert->is_visible, 404);

        return view('alertes.show', [
            'alert' => $alert,
            'others' => Alert::current()->urgentFirst()->whereKeyNot($alert->id)->take(3)->get(),
        ]);
    }

    /** Alertes en cours (interrogé par l'accueil toutes les 20 s) */
    public function active(): JsonResponse
    {
        $alerts = Alert::current()->urgentFirst()->take(5)->get()->map(fn ($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'summary' => $a->summary,
            'level' => $a->level,
            'category' => $a->category,
            'url' => route('alerts.show', $a),
            'updated' => (int) $a->updated_at?->timestamp,
        ]);

        return response()->json(['alerts' => $alerts])->header('Cache-Control', 'no-store');
    }
}