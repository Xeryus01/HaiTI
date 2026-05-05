<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start_date',
        'technician_1',
        'technician_2',
        'technician_3',
    ];

    protected $casts = [
        'week_start_date' => 'date:Y-m-d',
    ];

    // Get current week schedule
    public static function getCurrentWeek()
    {
        $currentDate = now();
        $weekStart = $currentDate->copy()->startOfWeek(); // Monday

        return self::whereDate('week_start_date', $weekStart->toDateString())
            ->first() ?? self::createDefault($weekStart);
    }

    // Create default schedule if not exists
    public static function createDefault($weekStart)
    {
        $technicians = self::getTechnicians();
        
        return self::firstOrCreate(
            ['week_start_date' => $weekStart->toDateString()],
            [
                'technician_1' => $technicians[0] ?? 'Fadil Rahman',
                'technician_2' => $technicians[1] ?? 'Marko Santoso',
                'technician_3' => $technicians[2] ?? 'Eji Wijaya',
            ]
        );
    }

    // Get list of all technicians from users with Teknisi role
    public static function getTechnicians()
    {
        return User::role('Teknisi')
            ->orderBy('name')
            ->pluck('name')
            ->toArray();
    }
}
