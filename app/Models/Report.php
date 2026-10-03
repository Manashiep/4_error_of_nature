<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'reference',
        'user_id',
        'service_id',
        'title',
        'category',
        'description',
        'location',
        'image_path',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function ($report) {
            if (empty($report->reference)) {
                // Génère une référence unique du type : SIG-7K9A2P
                $report->reference = 'SIG-' . strtoupper(Str::random(6));
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
}
