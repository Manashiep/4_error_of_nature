<?php

namespace Database\Seeders;

use App\Models\GlossaryTerm;
use Illuminate\Database\Seeder;

class GlossaryTermSeeder extends Seeder
{
    public function run(): void
    {
        $terms = [
            ['Démarche', 'Ce que vous faites pour obtenir quelque chose de la mairie (une demande, un rendez-vous, un signalement).'],
            ['Signalement', 'Un message pour prévenir la ville d\'un problème dans votre quartier, par exemple un lampadaire cassé.'],
            ['Espace personnel', 'Votre page privée, où vous retrouvez vos demandes et leur avancement.'],
            ['Numéro de suivi', 'Un code donné après l\'envoi d\'une demande. Il prouve que votre demande a bien été reçue.'],
            ['Service municipal', 'Un service de la mairie, comme la voirie, la santé ou l\'état civil.'],
            ['Agent municipal', 'Une personne qui travaille pour la ville et traite vos demandes.'],
        ];

        foreach ($terms as $i => [$term, $definition]) {
            GlossaryTerm::updateOrCreate(['term' => $term], ['definition' => $definition, 'sort_order' => $i]);
        }
    }
}
