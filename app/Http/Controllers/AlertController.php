<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;

class AlertController extends Controller
{
    /** Alertes visibles en ce moment (interrogé par l'accueil toutes les 20 s) */
    public function active(): JsonResponse
    {
        $alerts = Alert::current()->urgentFirst()->take(5)->get()->map(fn ($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'summary' => $a->summary,
            'level' => $a->level,
            'category' => $a->category,
            'updated' => (int) $a->updated_at?->timestamp,
        ]);

        return response()->json(['alerts' => $alerts])->header('Cache-Control', 'no-store');
    }
}