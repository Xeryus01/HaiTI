<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'code',
        'requester_id',
        'room_name',
        'purpose',
        'start_time',
        'end_time',
        'status',
        'approver_id',
        'notes',
        'zoom_link',
        'nota_dinas_path',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public static function statusLabels(): array
    {
        return [
            'PENDING' => 'Menunggu Tindak Lanjut',
            'APPROVED' => 'Disetujui',
            'REJECTED' => 'Ditolak',
            'COMPLETED' => 'Selesai',
            'CANCELLED' => 'Dibatalkan',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'entity_id')->where('entity_type', 'Reservation');
    }

    public static function generateCode(): string
    {
        $prefix = 'RES';
        $date = now()->format('Ymd');

        $sequence = self::whereDate('created_at', now())->count() + 1;
        $code = sprintf('%s-%s-%05d', $prefix, $date, $sequence);

        while (self::where('code', $code)->exists()) {
            $sequence++;
            $code = sprintf('%s-%s-%05d', $prefix, $date, $sequence);
        }

        return $code;
    }
}
