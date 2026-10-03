<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'service_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'requested_at',
        'confirmed_at',
        'rejection_reason',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
