<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeeklyPiketScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technicians = ['Fadil Rahman', 'Marko Santoso', 'Eji Wijaya', 'Mesra Putri'];
        $currentDate = now();

        // Create schedules for the next 12 weeks
        for ($i = 0; $i < 12; $i++) {
            $weekStart = $currentDate->copy()->addWeeks($i)->startOfWeek();
            
            // Rotate technicians each week
            $tech1 = $technicians[$i % count($technicians)];
            $tech2 = $technicians[($i + 1) % count($technicians)];
            $tech3 = $technicians[($i + 2) % count($technicians)];

            try {
                \App\Models\PiketSchedule::updateOrCreate(
                    ['week_start_date' => $weekStart->toDateString()],
                    [
                        'technician_1' => $tech1,
                        'technician_2' => $tech2,
                        'technician_3' => $tech3,
                    ]
                );
            } catch (\Exception $e) {
                // If updateOrCreate fails due to constraint, try to update existing record
                $existing = \App\Models\PiketSchedule::where('week_start_date', $weekStart->toDateString())->first();
                if ($existing) {
                    $existing->update([
                        'technician_1' => $tech1,
                        'technician_2' => $tech2,
                        'technician_3' => $tech3,
                    ]);
                }
            }
        }
    }
}
