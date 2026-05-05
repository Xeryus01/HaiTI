<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$schedule = App\Models\PiketSchedule::getCurrentWeek();
echo "Current Week Schedule:\n";
echo "Week Start: " . $schedule->week_start_date . "\n";
echo "Technician 1: " . $schedule->technician_1 . "\n";
echo "Technician 2: " . $schedule->technician_2 . "\n";
echo "Technician 3: " . $schedule->technician_3 . "\n";
?>