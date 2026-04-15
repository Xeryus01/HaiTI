<?php

namespace Database\Seeders;

use App\Models\PiketSchedule;
use Illuminate\Database\Seeder;

class PiketScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default schedules for all 12 months of 2026
        $technicians = [
            'lantai_1' => 'Marko Santoso',
            'lantai_2' => 'Fadil Rahman',
            'tu' => 'Eji Wijaya',
        ];

        for ($month = 1; $month <= 12; $month++) {
            PiketSchedule::updateOrCreate(
                ['month' => $month, 'year' => 2026],
                $technicians
            );
        }
    }
}