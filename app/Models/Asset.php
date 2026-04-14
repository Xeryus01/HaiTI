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
        'holder',
        'status',
        'condition',
        'purchased_at',
    ];

    protected $casts = [
        'specs' => 'array',
        'purchased_at' => 'date',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'ACTIVE' => 'Aktif',
            'INACTIVE' => 'Tidak Aktif',
            'PENDING' => 'Menunggu',
            'DECOMMISSIONED' => 'Dikeluarkan',
            default => ucfirst(strtolower($this->status)),
        };
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'GOOD' => 'Baik',
            'FAIR' => 'Cukup',
            'POOR' => 'Buruk',
            'DAMAGED' => 'Rusak',
            default => ucfirst(strtolower($this->condition)),
        };
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'entity_id')->where('entity_type', 'Asset');
    }

    public function maintenanceLogs()
    {
        return $this->logs();
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    protected static function booted()
    {
        static::updated(function () {
            \Cache::forget('assets.all');
        });

        static::deleted(function () {
            \Cache::forget('assets.all');
        });

        static::created(function () {
            \Cache::forget('assets.all');
        });
    }
}
