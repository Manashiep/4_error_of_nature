<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_banner' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Annonces déjà publiées (D06). */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('published_at', '<=', now());
    }

    /** Messages à afficher en bandeau sur toutes les pages (D18, F29, F31) : alertes d'abord. */
    public function scopeBanner(Builder $q): Builder
    {
        return $q->published()
            ->where('is_banner', true)
            ->where(fn ($w) => $w->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->orderByRaw("case level when 'alerte' then 0 else 1 end")
            ->latest('published_at');
    }
}
