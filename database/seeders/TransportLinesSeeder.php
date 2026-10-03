<?php

namespace Database\Seeders;

use App\Models\Transport;
use Illuminate\Database\Seeder;

class TransportLinesSeeder extends Seeder
{
    public function run(): void
    {
        Transport::updateOrCreate(['code' => 'L-01'], [
            'name' => 'Centre-ville ↔ Port',
            'type' => 'Bus',
            'frequency' => 'Toutes les 20 min',
            'route_description' => 'Mairie, marché central, hôpital, port.',
            'schedules' => [
                ['days' => 'Lundi - Vendredi', 'hours' => '06:00 à 21:00, toutes les 20 min'],
                ['days' => 'Samedi', 'hours' => '07:00 à 19:00, toutes les 30 min'],
                ['days' => 'Dimanche & Fériés', 'hours' => '08:00 à 17:00, toutes les 60 min'],
            ],
            'status' => 'Normal',
            'is_active' => true,
        ]);

        Transport::updateOrCreate(['code' => 'N-02'], [
            'name' => 'Navette côtière',
            'type' => 'Navette',
            'frequency' => 'Toutes les 45 min',
            'route_description' => 'Port, plage nord, village de pêcheurs.',
            'schedules' => [
                ['days' => 'Tous les jours', 'hours' => '07:00 à 18:00, toutes les 45 min'],
            ],
            'status' => 'Perturbé',
            'status_message' => 'Retards de 15 minutes suite à des travaux sur la route côtière.',
            'is_active' => true,
        ]);

        Transport::updateOrCreate(['code' => 'S-03'], [
            'name' => 'Ramassage scolaire nord',
            'type' => 'Scolaire',
            'frequency' => 'Matin et soir',
            'route_description' => 'Quartiers nord vers les écoles du centre.',
            'schedules' => [
                ['days' => 'Lundi - Vendredi', 'hours' => '06:45 et 07:15 (matin) ; 16:30 et 17:00 (soir)'],
            ],
            'status' => 'Normal',
            'is_active' => true,
        ]);
    }
}