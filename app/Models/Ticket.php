<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    // status constants for improved consistency
    public const STATUS_OPEN = 'OPEN';
    public const STATUS_ASSIGNED_DETECT = 'ASSIGNED_DETECT';
    public const STATUS_SOLVED_WITH_NOTES = 'SOLVED_WITH_NOTES';
    public const STATUS_SOLVED = 'SOLVED';
    public const STATUS_REJECTED = 'REJECTED';

    /**
     * All valid ticket statuses, used for validation and dropdowns.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_ASSIGNED_DETECT,
            self::STATUS_SOLVED_WITH_NOTES,
            self::STATUS_SOLVED,
            self::STATUS_REJECTED,
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_OPEN => 'Menunggu',
            self::STATUS_ASSIGNED_DETECT => 'Diproses Teknisi',
            self::STATUS_SOLVED_WITH_NOTES => 'Selesai dengan Catatan',
            self::STATUS_SOLVED => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public static function priorityLabels(): array
    {
        return [
            'LOW' => 'Rendah',
            'MEDIUM' => 'Sedang',
            'HIGH' => 'Tinggi',
            'CRITICAL' => 'Darurat',
        ];
    }

    public static function categoryLabels(): array
    {
        return [
            'MAINTENANCE' => 'Perawatan',
            'ZOOM_SUPPORT' => 'Bantuan Zoom',
            'IT_SUPPORT' => 'Perbaikan IT',
            'OTHER' => 'Lainnya',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::priorityLabels()[$this->priority] ?? $this->priority;
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categoryLabels()[$this->category] ?? $this->category;
    }

    protected $fillable = [
        'code',
        'requester_id',
        'assignee_id',
        'asset_id',
        'category',
        'title',
        'description',
        'priority',
        'status',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'entity_id')->where('entity_type', 'Ticket');
    }

    public static function generateCode(): string
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');

        // Generate a daily sequence number; fallback when collisions happen.
        $sequence = self::whereDate('created_at', now())->count() + 1;
        $code = sprintf('%s-%s-%05d', $prefix, $date, $sequence);

        while (self::where('code', $code)->exists()) {
            $sequence++;
            $code = sprintf('%s-%s-%05d', $prefix, $date, $sequence);
        }

        return $code;
    }
}
