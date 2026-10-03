<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'image_path',
        'category',
        'contact_email',
        'contact_phone',
        'views_count',
        'is_active',
        'is_featured',
        'translations',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'translations' => 'array',
    ];

    /**
     * Incrémente le compteur de clics du service
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
