<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Signalement d'un habitant (F25). Statuts : nouveau, en_cours, traite. */
class Report extends Model
{
    protected $guarded = [];

    public const CATEGORIES = ['Éclairage public', 'Voirie', 'Eau et fuites', 'Déchets et propreté', 'Autre'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
