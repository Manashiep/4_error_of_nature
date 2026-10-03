<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // --- Annonces ---
            ['slug' => 'maintenance-reseau-energie-nord', 'title' => "Maintenance du réseau d'énergie, secteur Nord", 'category' => 'Énergie', 'level' => 'important',
                'summary' => 'Coupures brèves possibles cette nuit entre 2 h et 4 h.',
                'action' => 'Prévoyez une lampe et rechargez vos appareils avant ce soir.',
                'body' => "Des coupures brèves sont possibles cette nuit entre 2 h et 4 h dans le secteur Nord, le temps de la maintenance du réseau.\n\nLes services essentiels restent alimentés. Le courant sera rétabli dès la fin des opérations.",
                'published_at' => '2026-10-03 08:00'],
            ['slug' => 'horaires-tram-2', 'title' => 'Nouveaux horaires de la ligne de tram 2', 'category' => 'Transports', 'level' => 'info',
                'summary' => 'Un départ toutes les 10 minutes aux heures de pointe.',
                'body' => "Un départ toutes les 10 minutes aux heures de pointe, toutes les 20 minutes en soirée.\n\nConsultez la page Transports pour les prochains départs en temps réel.",
                'published_at' => '2026-10-02 09:00'],
            ['slug' => 'guichet-unique-habitants', 'title' => 'Ouverture du guichet unique des habitants', 'category' => 'Démarches', 'level' => 'info',
                'summary' => 'Un seul accès pour toutes vos démarches en ligne.',
                'body' => "Un seul accès en ligne pour vos démarches, avec un espace personnel pour suivre vos demandes.\n\nCréez votre compte pour commencer.",
                'published_at' => '2026-09-30 09:00'],
            ['slug' => 'calendrier-collecte-dechets', 'title' => 'Calendrier de collecte des déchets', 'category' => 'Environnement', 'level' => 'info',
                'summary' => 'La collecte passe au mardi et au vendredi dans les quartiers Est.',
                'body' => "La collecte passe désormais le mardi et le vendredi dans les quartiers Est.\n\nSortez vos bacs la veille au soir.",
                'published_at' => '2026-09-27 09:00'],
            ['slug' => 'controle-qualite-air', 'title' => "Campagne de contrôle de la qualité de l'air", 'category' => 'Santé', 'level' => 'info',
                'summary' => 'Des mesures sont réalisées dans tous les quartiers pendant deux semaines.',
                'body' => "Des mesures de la qualité de l'air sont réalisées dans tous les quartiers pendant deux semaines.\n\nLes résultats seront publiés sur cette page.",
                'published_at' => '2026-09-24 09:00'],

            // --- Exemples d'alertes en bandeau (scénarios de l'API, à retirer : is_banner = false) ---
            ['slug' => 'alerte-montee-des-eaux-sud', 'title' => "Montée du niveau de l'eau, quartier sud", 'category' => 'Alerte', 'level' => 'alerte', 'is_banner' => true,
                'summary' => "Une montée inhabituelle du niveau de l'eau est observée dans le quartier sud.",
                'action' => 'Éloignez-vous des berges et évitez les sous-sols du quartier sud. Suivez les consignes de la mairie.',
                'body' => "Le centre de surveillance environnementale observe une montée inhabituelle du niveau de l'eau dans le quartier sud.\n\nÉloignez-vous des berges, évitez les sous-sols et les parkings souterrains, et ne traversez pas les zones inondées.\n\nCette page sera mise à jour dès que la situation évolue.",
                'published_at' => '2026-10-03 09:30'],
            ['slug' => 'alerte-vague-de-chaleur', 'title' => 'Vague de chaleur extrême', 'category' => 'Alerte', 'level' => 'alerte', 'is_banner' => true,
                'summary' => 'Une vague de chaleur extrême touche plusieurs secteurs de la ville.',
                'action' => 'Buvez régulièrement, restez au frais et prenez des nouvelles des personnes âgées ou fragiles.',
                'body' => "Une vague de chaleur extrême touche actuellement plusieurs secteurs de la ville.\n\nBuvez régulièrement sans attendre d'avoir soif, évitez les efforts aux heures les plus chaudes et fermez volets et fenêtres en journée.\n\nPrenez des nouvelles des personnes âgées, des jeunes enfants et des personnes isolées de votre entourage.",
                'published_at' => '2026-10-03 10:00'],
        ];

        foreach ($rows as $r) {
            Announcement::updateOrCreate(['slug' => $r['slug']], $r + ['is_banner' => $r['is_banner'] ?? false]);
        }
    }
}
