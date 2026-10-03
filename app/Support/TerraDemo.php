<?php

namespace App\Support;

/** Données de démonstration pour la visu. BACK : remplacer par les modèles Eloquent (même structure de tableau). */
class TerraDemo
{
    public static function services(): array
    {
        return [
            ['id'=>'signalement','icon'=>'🚨','name'=>'Signalement','desc'=>'Signaler une panne, une fuite ou un équipement défaillant.','cat'=>'vie','cat_label'=>'Vie quotidienne','hours'=>'24 h/24'],
            ['id'=>'demarches','icon'=>'📄','name'=>'État civil et démarches','desc'=>'Attestations, justificatifs et dossiers administratifs.','cat'=>'vie','cat_label'=>'Vie quotidienne','hours'=>'8 h – 18 h'],
            ['id'=>'transports','icon'=>'🚆','name'=>'Transports','desc'=>'Lignes, horaires et état du trafic.','cat'=>'mobilite','cat_label'=>'Mobilité','hours'=>'5 h – 1 h'],
            ['id'=>'logement','icon'=>'🏠','name'=>'Logement','desc'=>"Demande de logement, aides et signalement d'insalubrité.",'cat'=>'habitat','cat_label'=>'Habitat','hours'=>'9 h – 17 h'],
            ['id'=>'eau-energie','icon'=>'💧','name'=>'Eau et énergie','desc'=>'Consommation, coupures programmées et incidents.','cat'=>'habitat','cat_label'=>'Habitat','hours'=>'24 h/24'],
            ['id'=>'sante','icon'=>'🩺','name'=>'Santé','desc'=>'Centres de soins, rendez-vous et urgences.','cat'=>'sante','cat_label'=>'Santé','hours'=>'24 h/24'],
            ['id'=>'environnement','icon'=>'🌿','name'=>'Environnement','desc'=>"Qualité de l'air, déchets et espaces verts du dôme.",'cat'=>'vie','cat_label'=>'Vie quotidienne','hours'=>'8 h – 18 h'],
            ['id'=>'education','icon'=>'🎓','name'=>'Éducation','desc'=>'Inscriptions scolaires et activités pour les jeunes.','cat'=>'vie','cat_label'=>'Vie quotidienne','hours'=>'8 h – 17 h'],
        ];
    }

    public static function announcements(): array
    {
        return [
            ['date'=>'2026-10-03','title'=>"Maintenance du réseau d'énergie, secteur Nord",'excerpt'=>'Coupures brèves possibles cette nuit entre 2 h et 4 h.','tag'=>'Énergie','important'=>true],
            ['date'=>'2026-10-02','title'=>'Nouveaux horaires de la ligne de tram 2','excerpt'=>'Un départ toutes les 10 minutes aux heures de pointe.','tag'=>'Transports','important'=>false],
            ['date'=>'2026-09-30','title'=>'Ouverture du guichet unique des habitants','excerpt'=>'Un seul accès en ligne pour toutes vos démarches.','tag'=>'Démarches','important'=>false],
            ['date'=>'2026-09-27','title'=>'Calendrier de collecte des déchets','excerpt'=>'La collecte passe au mardi et au vendredi dans les quartiers Est.','tag'=>'Environnement','important'=>false],
        ];
    }

    public static function requests(): array
    {
        return [
            ['ref'=>'REQ-0012','subject'=>'Lampadaire en panne rue des Hublots','status'=>'en-cours','date'=>'2026-10-02'],
            ['ref'=>'REQ-0007','subject'=>"Demande d'attestation de résidence",'status'=>'traite','date'=>'2026-09-29'],
            ['ref'=>'REQ-0015','subject'=>'Question sur la collecte des déchets','status'=>'nouveau','date'=>'2026-10-03'],
        ];
    }
}
