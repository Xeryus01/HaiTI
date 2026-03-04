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
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

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

    public function generateCode()
    {
        $timestamp = date('ymdHi');
        $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        return 'RES' . $timestamp . $random;
    }
}
