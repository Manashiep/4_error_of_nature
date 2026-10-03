<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransportLine extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'frequency', 'route_description',
        'schedules', 'status', 'status_message', 'is_active',
    ];

    protected $casts = [
        'schedules' => 'array',
        'is_active' => 'boolean',
    ];

    /** Lignes visibles par le public */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    /** Lignes dont le trafic n'est pas normal */
    public function scopeDisrupted(Builder $q): Builder
    {
        return $q->where('status', '!=', 'Normal');
    }

    /** Classes Tailwind du badge d'état */
    public function getStatusClassesAttribute(): string
    {
        return match ($this->status) {
            'Interrompu' => 'border-coral text-coral',
            'Perturbé' => 'border-warn text-warn',
            default => 'border-emerald-500 text-emerald-500',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'Interrompu' => 'Ligne interrompue',
            'Perturbé' => 'Trafic perturbé',
            default => 'Trafic normal',
        };
    }
}