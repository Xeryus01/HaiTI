<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\PiketSchedule::orderBy('week_start_date')->get() as $sched) {
    echo $sched->week_start_date . ' -> ' . implode(', ', [$sched->technician_1, $sched->technician_2, $sched->technician_3]) . PHP_EOL;
}
