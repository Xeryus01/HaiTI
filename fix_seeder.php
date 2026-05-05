<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Clear existing data first
    \App\Models\PiketSchedule::truncate();
    echo "Cleared existing data.\n";

    $technicians = ['Fadil Rahman', 'Marko Santoso', 'Eji Wijaya', 'Mesra Putri'];
    $currentDate = now();

    // Create schedules for the next 12 weeks
    for ($i = 0; $i < 12; $i++) {
        $weekStart = $currentDate->copy()->addWeeks($i)->startOfWeek();

        // Rotate technicians each week
        $tech1 = $technicians[$i % count($technicians)];
        $tech2 = $technicians[($i + 1) % count($technicians)];
        $tech3 = $technicians[($i + 2) % count($technicians)];

        \App\Models\PiketSchedule::create([
            'week_start_date' => $weekStart->toDateString(),
            'technician_1' => $tech1,
            'technician_2' => $tech2,
            'technician_3' => $tech3,
        ]);

        echo "Created: Week {$weekStart->toDateString()} - {$tech1}, {$tech2}, {$tech3}\n";
    }

    echo "Seeder completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>