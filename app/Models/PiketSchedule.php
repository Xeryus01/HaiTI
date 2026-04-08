<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'lantai_1',
        'lantai_2',
        'tu',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
    ];

    // Get current month schedule
    public static function getCurrentMonth()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        return self::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first() ?? self::createDefault($currentMonth, $currentYear);
    }

    // Create default schedule if not exists
    public static function createDefault($month, $year)
    {
        return self::create([
            'month' => $month,
            'year' => $year,
            'lantai_1' => 'Fadil',
            'lantai_2' => 'Marko',
            'tu' => 'Eji',
        ]);
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
