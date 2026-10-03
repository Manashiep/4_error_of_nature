<?php

namespace App\Models;

use App\Models\User;
use App\Notifications\AlertPublished;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Alert extends Model
{
    protected $fillable = [
        'title', 'summary', 'body', 'category', 'level',
        'is_active', 'published_at', 'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $a) {
            if (empty($a->published_at)) {
                $a->published_at = now();
            }
        });

        // Notifie tous les utilisateurs quand une alerte active est diffusée
        static::created(function (self $a) {
            if ($a->is_active && $a->published_at->lte(now())) {
                User::query()->chunkById(200, fn ($users) => Notification::send($users, new AlertPublished($a)));
            }
        });
    }

    public function scopeCurrent(Builder $q): Builder
    {
        return $q->where('is_active', true)
            ->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->where(fn ($w) => $w->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    public function scopeUrgentFirst(Builder $q): Builder
    {
        return $q->orderByRaw("CASE level WHEN 'alerte' THEN 0 WHEN 'important' THEN 1 ELSE 2 END")
            ->latest('published_at');
    }
}