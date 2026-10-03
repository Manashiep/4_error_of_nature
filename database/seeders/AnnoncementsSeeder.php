<?php

namespace Database\Seeders;

use App\Models\Annoncements;
use Illuminate\Database\Seeder;

class AnnoncementsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Travaux sur la route principale', 'Travaux',
                "La circulation sera perturbée du lundi au vendredi.\n\nMerci d'emprunter les itinéraires de déviation indiqués sur place."],
            ['Alerte : coupure d\'eau exceptionnelle', 'Alerte',
                "Une coupure d'eau aura lieu demain de 8h à 14h dans plusieurs quartiers.\n\nPensez à faire des réserves d'eau."],
            ['Festival culturel de la ville', 'Culture',
                "Le festival annuel ouvre ses portes ce week-end avec concerts, expositions et ateliers pour tous."],
            ['Nouveaux horaires de la mairie', 'Services',
                "À partir du mois prochain, la mairie sera ouverte de 8h à 16h du lundi au vendredi."],
            ['Bienvenue sur le portail citoyen', 'Général',
                "Retrouvez ici toutes les informations pratiques et les services de la ville."],
        ];

        foreach ($items as $i => [$title, $category, $content]) {
            Annoncements::create([
                'title' => $title,
                'category' => $category,
                'content' => $content,
                'is_published' => true,
                'published_at' => now()->subDays($i),
            ]);
        }
    }
}