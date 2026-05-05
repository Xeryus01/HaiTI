<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$schedules = App\Models\PiketSchedule::all();
echo "Current Piket Schedules:\n";
foreach ($schedules as $schedule) {
    echo "ID: {$schedule->id}, Week: {$schedule->week_start_date}, Tech1: {$schedule->technician_1}, Tech2: {$schedule->technician_2}, Tech3: {$schedule->technician_3}\n";
}

echo "\nFixing data...\n";

// Clear existing data and reseed
App\Models\PiketSchedule::truncate();

$technicians = ['Fadil Rahman', 'Marko Santoso', 'Eji Wijaya', 'Mesra Putri'];
$currentDate = now();

// Create schedules for the next 12 weeks
for ($i = 0; $i < 12; $i++) {
    $weekStart = $currentDate->copy()->addWeeks($i)->startOfWeek();

    // Rotate technicians each week
    $tech1 = $technicians[$i % count($technicians)];
    $tech2 = $technicians[($i + 1) % count($technicians)];
    $tech3 = $technicians[($i + 2) % count($technicians)];

    App\Models\PiketSchedule::create([
        'week_start_date' => $weekStart->toDateString(),
        'technician_1' => $tech1,
        'technician_2' => $tech2,
        'technician_3' => $tech3,
    ]);

    echo "Created: Week {$weekStart->toDateString()} - {$tech1}, {$tech2}, {$tech3}\n";
}

echo "Done!\n";