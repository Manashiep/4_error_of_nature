<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Support\ServiceCatalog;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // Services mis en avant (F28) + accès rapides aux annonces et au contact
        $services = ServiceCatalog::featured()->map(fn ($s) => [
            'ico' => $s['ico'], 'titre' => $s['tile_title'], 'desc' => $s['tile_desc'], 'href' => $s['tile'], 'cta' => $s['tile_cta'],
        ])->push(
            ['ico' => '📰', 'titre' => 'Annonces de la ville', 'desc' => 'Infos pratiques et changements de service.', 'href' => '/actualites', 'cta' => 'Lire'],
            ['ico' => '✉️', 'titre' => 'Contacter la mairie', 'desc' => 'Une question ? Écrivez aux services.', 'href' => '/contact', 'cta' => 'Écrire'],
        )->all();

        // D06 : dernières annonces publiées
        $annonces = Announcement::published()->latest('published_at')->take(3)->get()->map(fn ($a) => [
            'date' => $a->published_at->toDateString(),
            'titre' => $a->title,
            'resume' => $a->summary,
            'url' => route('annonces.show', $a),
        ])->all();

        return view('home', compact('services', 'annonces'));
    }
}
