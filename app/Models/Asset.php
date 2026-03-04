<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'asset_code',
        'name',
        'type',
        'brand',
        'model',
        'serial_number',
        'specs',
        'location',
        'status',
        'purchased_at',
    ];

    protected $casts = [
        'specs' => 'array',
        'purchased_at' => 'date',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(Log::class, 'entity_id')->where('entity_type', 'Asset');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
