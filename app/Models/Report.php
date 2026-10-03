<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $fillable = [
        'reference', 'user_id', 'service_id', 'title', 'category',
        'description', 'location', 'image_path', 'status',
    ];

    // Statut par défaut : en attente
    protected $attributes = ['status' => 'pending'];

    public const STATUSES = [
        'pending'     => 'En attente',
        'in_progress' => 'En cours',
        'resolved'    => 'Résolu',
        'rejected'    => 'Rejeté',
    ];

    public const CATEGORIES = [
        'Éclairage public',
        'Voirie et trottoirs',
        'Déchets et propreté',
        'Fuite d\'eau',
        'Espaces verts',
        'Bruit et nuisances',
        'Autre',
    ];

    protected static function booted(): void
    {
        static::creating(function ($report) {
            if (empty($report->reference)) {
                $report->reference = 'SIG-'.strtoupper(Str::random(6));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function supports(): HasMany
    {
        return $this->hasMany(ReportSupport::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusToneAttribute(): string
    {
        return match ($this->status) {
            'resolved'    => 'border-ok/70 text-ok',
            'in_progress' => 'border-violet/70 text-violet',
            'rejected'    => 'border-red-500/70 text-red-400',
            default       => 'border-cyan/60 text-cyan',
        };
    }

    public function getIsClosedAttribute(): bool
    {
        return in_array($this->status, ['resolved', 'rejected'], true);
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }
}