<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Annoncements extends Model
{
    protected $table = 'annoncements';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'category',
        'image_path',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $a) {
            if (empty($a->slug)) {
                $a->slug = Str::slug($a->title) . '-' . Str::lower(Str::random(5));
            }
            if (empty($a->published_at) && $a->is_published) {
                $a->published_at = now();
            }
            if (empty($a->user_id) && Auth::check()) {
                $a->user_id = Auth::id();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Annonces visibles par le public */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ---- Accesseurs de compatibilité avec les vues ----

    public function getBodyAttribute(): string
    {
        return (string) $this->content;
    }

    public function getSummaryAttribute(): string
    {
        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->content))), 160);
    }

    public function getLevelAttribute(): string
    {
        return match ($this->category) {
            'Alerte' => 'alerte',
            'Travaux', 'Services' => 'important',
            default => 'info',
        };
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}