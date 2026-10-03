<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class create_contacts_table extends Model
{
    protected $fillable = [
        'type',
        'service_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
