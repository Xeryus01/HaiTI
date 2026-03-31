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

    public function generateCode()
    {
        $prefix = substr($this->category, 0, 3);
        $timestamp = date('ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return strtoupper($prefix . $timestamp . $random);
    }
}
