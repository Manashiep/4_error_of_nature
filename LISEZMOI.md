# Nova Terra – pages publiques (design Halo)

Copier le contenu de ce dossier à la racine du projet Laravel (remplacer les fichiers existants), puis :

    php artisan migrate
    php artisan db:seed --class=GlossaryTermSeeder
    php artisan route:clear && php artisan view:clear

## À SUPPRIMER (remplacés, données en dur)
- app/Http/Controllers/HomeController.php, ServiceController.php, TransportController.php
- app/Support/ServiceCatalog.php (après avoir vérifié avec une recherche « ServiceCatalog » qu'il n'est plus utilisé)
- resources/views/transports.blade.php
- resources/views/partials/public/panel.blade.php (doublon du composant x-public.panel)

## À vérifier
- Anciennes références : route('services') -> route('services.index'), liens vers « transports » à retirer.
- Modèle Announcement : casts datetime + scopes published() / banner() (déjà OK chez toi).
- Format des traductions des services : {"en": {"name": "...", "short_description": "...", "description": "..."}}
- Les tables services / announcements doivent contenir des données pour voir le rendu.
