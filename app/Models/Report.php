<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Signalement d'un habitant (F25). Statuts : nouveau, en_cours, traite. */
class Report extends Model
{
    protected $guarded = [];

    public const CATEGORIES = ['Éclairage public', 'Voirie', 'Eau et fuites', 'Déchets et propreté', 'Autre'];

    public const STATUSES = ['nouveau' => 'Reçue', 'en_cours' => 'En cours de traitement', 'traite' => 'Traitée'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supports(): HasMany
    {
        return $this->hasMany(ReportSupport::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }
}
