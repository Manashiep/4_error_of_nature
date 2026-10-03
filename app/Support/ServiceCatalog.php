<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/** Catalogue des services municipaux (D05). Les "featured" sont mis en avant (F28). */
class ServiceCatalog
{
    public const CATEGORIES = [
        'vie' => 'Vie quotidienne',
        'mobilite' => 'Mobilité',
        'habitat' => 'Habitat',
        'sante' => 'Santé',
    ];

    public static function all(): Collection
    {
        return collect([
            ['rank' => 1, 'slug' => 'signalement', 'ico' => '🚨', 'title' => 'Signalement', 'cat' => 'vie', 'featured' => true, 'hours' => '24 h/24 · délai moyen 48 h',
                'desc' => 'Signaler une panne, une fuite ou un équipement défaillant.', 'href' => '/signalement', 'cta' => 'Signaler un problème',
                'tile' => '/signalement', 'tile_title' => 'Signaler un problème', 'tile_desc' => 'Panne, fuite, éclairage, voirie.', 'tile_cta' => 'Signaler',
                'keywords' => 'panne fuite lampadaire éclairage voirie déchets problème casse'],
            ['rank' => 2, 'slug' => 'demarches', 'ico' => '📄', 'title' => 'État civil et démarches', 'cat' => 'vie', 'featured' => true, 'hours' => '8 h – 18 h',
                'desc' => 'Attestations, justificatifs et dossiers administratifs.', 'href' => '/contact?service=demarches', 'cta' => 'Contacter ce service',
                'tile' => '/services#demarches', 'tile_title' => 'Mes démarches', 'tile_desc' => 'État civil, attestations, dossiers.', 'tile_cta' => 'Commencer',
                'keywords' => 'état civil attestation papiers dossier naissance mariage justificatif'],
            ['rank' => 3, 'slug' => 'sante', 'ico' => '🩺', 'title' => 'Santé', 'cat' => 'sante', 'featured' => true, 'hours' => '24 h/24',
                'desc' => 'Centres de soins, rendez-vous et urgences.', 'href' => '/contact?service=sante', 'cta' => 'Contacter ce service',
                'tile' => '/services?cat=sante', 'tile_title' => 'Santé', 'tile_desc' => 'Centres de soins, vaccination, urgences.', 'tile_cta' => 'Trouver',
                'keywords' => 'santé médecin hôpital soins vaccination urgence centre de soins pharmacie'],
            ['rank' => 4, 'slug' => 'transports', 'ico' => '🚆', 'title' => 'Transports', 'cat' => 'mobilite', 'featured' => true, 'hours' => '5 h – 1 h',
                'desc' => 'Lignes, horaires et état du trafic.', 'href' => '/transports', 'cta' => 'Voir les horaires',
                'tile' => '/transports', 'tile_title' => 'Transports', 'tile_desc' => 'Lignes, horaires, perturbations.', 'tile_cta' => 'Consulter',
                'keywords' => 'tram bus horaires ligne trafic mobilité déplacement'],
            ['rank' => 5, 'slug' => 'logement', 'ico' => '🏠', 'title' => 'Logement', 'cat' => 'habitat', 'featured' => false, 'hours' => '9 h – 17 h',
                'desc' => "Demande de logement, aides et signalement d'insalubrité.", 'href' => '/contact?service=logement', 'cta' => 'Contacter ce service',
                'keywords' => 'logement habitat aide loyer insalubrité appartement'],
            ['rank' => 6, 'slug' => 'eau-energie', 'ico' => '💧', 'title' => 'Eau et énergie', 'cat' => 'habitat', 'featured' => false, 'hours' => '24 h/24',
                'desc' => 'Consommation, coupures programmées et incidents.', 'href' => '/contact?service=eau-energie', 'cta' => 'Contacter ce service',
                'keywords' => 'eau électricité énergie coupure consommation compteur'],
            ['rank' => 7, 'slug' => 'environnement', 'ico' => '🌿', 'title' => 'Environnement', 'cat' => 'vie', 'featured' => false, 'hours' => '8 h – 18 h',
                'desc' => "Qualité de l'air, déchets et espaces verts du dôme.", 'href' => '/contact?service=environnement', 'cta' => 'Contacter ce service',
                'keywords' => 'environnement air déchets poubelle collecte espaces verts parc'],
            ['rank' => 8, 'slug' => 'education', 'ico' => '🎓', 'title' => 'Éducation', 'cat' => 'vie', 'featured' => false, 'hours' => '8 h – 17 h',
                'desc' => 'Inscriptions scolaires et activités pour les jeunes.', 'href' => '/contact?service=education', 'cta' => 'Contacter ce service',
                'keywords' => 'école éducation inscription scolaire jeunes activités enfants'],
        ]);
    }

    /** Services mis en avant, dans l'ordre de priorité (F28). */
    public static function featured(): Collection
    {
        return self::all()->where('featured', true)->sortBy('rank')->values();
    }

    /** Recherche insensible aux accents et à la casse (F32) + filtre de catégorie. */
    public static function search(string $q, string $cat): Collection
    {
        $norm = fn (string $s) => (string) Str::of($s)->ascii()->lower();
        $terms = array_filter(explode(' ', $norm($q)));

        return self::all()
            ->filter(fn ($s) => $cat === 'all' || $s['cat'] === $cat)
            ->filter(function ($s) use ($terms, $norm) {
                $hay = $norm($s['title'].' '.$s['desc'].' '.self::CATEGORIES[$s['cat']].' '.$s['keywords']);
                foreach ($terms as $t) {
                    if (! str_contains($hay, $t)) {
                        return false;
                    }
                }

                return true;
            })
            ->sortBy([['featured', 'desc'], ['rank', 'asc']])
            ->values();
    }
}
