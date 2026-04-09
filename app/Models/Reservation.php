<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'zoom_record_link',
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

        DB::beginTransaction();

        try {
            $sequence = DB::table('code_sequences')
                ->where('date', $date)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                DB::table('code_sequences')->insert([
                    'date' => $date,
                    'ticket_count' => 0,
                    'reservation_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $nextSequence = 1;
            } else {
                $nextSequence = $sequence->reservation_count + 1;
                DB::table('code_sequences')
                    ->where('date', $date)
                    ->update(['reservation_count' => $nextSequence, 'updated_at' => now()]);
            }

            DB::commit();

            return sprintf('%s-%s-%05d', $prefix, $date, $nextSequence);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
