<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'route_description',
        'frequency',
        'status',
        'status_message',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'schedules' => 'array',
    ];
}
