<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'week_start_date',
        'week_end_date',
        'technician_1',
        'technician_2',
        'technician_3',
    ];

    protected $casts = [
        'week_start_date' => 'date:Y-m-d',
        'week_end_date' => 'date:Y-m-d',
    ];

    // Get current week schedule
    public static function getCurrentWeek()
    {
        return self::forDate(now());
    }

    public static function forDate($date = null)
    {
        $currentDate = $date ? Carbon::parse($date) : now();
        $weekStart = $currentDate->copy()->startOfWeek(); // Monday

        return self::whereDate('week_start_date', $weekStart->toDateString())
            ->first() ?? self::createDefault($weekStart);
    }

    public static function findForDate($date = null)
    {
        $currentDate = $date ? Carbon::parse($date) : now();
        $weekStart = $currentDate->copy()->startOfWeek(); // Monday

        return self::whereDate('week_start_date', $weekStart->toDateString())
            ->first();
    }

    public static function scheduledTechniciansForDate($date = null)
    {
        $schedule = self::findForDate($date);

        return $schedule ? $schedule->scheduledUsers() : collect();
    }

    public function scheduledUserNames(): array
    {
        return collect([$this->technician_1, $this->technician_2, $this->technician_3])
            ->map(fn($name) => trim($name))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function scheduledUsers()
    {
        $names = $this->scheduledUserNames();

        if (empty($names)) {
            return User::role('Teknisi')->orderBy('name')->get();
        }

        $lowerNames = array_map(fn($name) => strtolower(trim($name)), $names);

        return User::role('Teknisi')
            ->where(function ($query) use ($lowerNames) {
                foreach ($lowerNames as $name) {
                    $query->orWhereRaw('LOWER(TRIM(name)) = ?', [$name]);
                }
            })
            ->orderBy('name')
            ->get();
    }

    // Create default schedule if not exists
    public static function createDefault($weekStart)
    {
        $technicians = self::getTechnicians();
        
        return self::firstOrCreate(
            ['week_start_date' => $weekStart->toDateString()],
            [
                'week_end_date' => $weekStart->copy()->endOfWeek()->toDateString(),
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
