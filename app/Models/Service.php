<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $guarded = [];

    protected $casts = [
        'translations' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];

    /** F28 : prioritaires d'abord, puis les plus consultés */
    public function scopeRanked(Builder $q): Builder
    {
        return $q->orderByDesc('is_featured')->orderByDesc('views_count')->orderBy('name');
    }

    /**
     * D14 / F27 : texte traduit si une traduction existe pour la langue choisie, sinon le français.
     * Format attendu en base : {"en": {"name": "...", "short_description": "...", "description": "..."}}
     */
    public function tr(string $field): ?string
    {
        $lang = session('lang');

        if ($lang && $lang !== 'fr') {
            $t = data_get($this->translations, $lang.'.'.$field);
            if (filled($t)) {
                return $t;
            }
        }

        return $this->{$field};
    }

    /** Langues réellement présentes dans la base */
    public static function languages(): array
    {
        return static::query()->whereNotNull('translations')->pluck('translations')
            ->flatMap(fn ($t) => array_keys((array) $t))
            ->reject(fn ($c) => $c === 'fr')->unique()->values()->all();
    }

    public static function languageLabel(string $code): string
    {
        return ['fr' => 'Français', 'en' => 'English', 'es' => 'Español', 'pt' => 'Português',
                'de' => 'Deutsch', 'it' => 'Italiano', 'ar' => 'العربية'][$code] ?? strtoupper($code);
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = $this->image_path;

        if (! filled($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, '/storage/')) {
            $path = Str::after($path, '/storage/');
        } elseif (Str::startsWith($path, '/')) {
            return $path;
        }

        // Les uploads Filament sont stockés sur le disque public, sans préfixe.
        $path = Str::after($path, 'public/');
        $path = Str::after($path, 'storage/');

        // Utilise l'origine de la requête courante plutôt qu'APP_URL, qui peut
        // contenir un hôte ou un port différent en local.
        return asset('storage/'.$path);
    }
}
