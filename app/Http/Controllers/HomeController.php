<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            // F28 : prioritaires d'abord, puis les plus consultés
            'services' => Service::ranked()->take(6)->get(),
            'topViewed' => Service::orderByDesc('views_count')->take(3)->get(),

            // D06 : dernières annonces publiées (collection de modèles, pas un tableau)
            'annonces' => Announcement::published()->latest('published_at')->take(4)->get(),

            'stats' => [
                'services' => Service::count(),
                'actifs' => Service::where('is_active', true)->count(),
                'annonces' => Announcement::published()->count(),
            ],
        ]);
    }
}
