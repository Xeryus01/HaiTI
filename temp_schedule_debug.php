<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

date_default_timezone_set('UTC');

$date = now();
$weekStart = $date->copy()->startOfWeek();
echo "Now: {$date}\n";
echo "StartOfWeek: {$weekStart}\n";

$sched = App\Models\PiketSchedule::whereDate('week_start_date', $weekStart->toDateString())->first();
if ($sched) {
    echo "Found schedule {$sched->week_start_date} -> {$sched->technician_1}, {$sched->technician_2}, {$sched->technician_3}\n";
    $names = collect([$sched->technician_1, $sched->technician_2, $sched->technician_3])->filter()->all();
    echo 'Names: ' . json_encode($names) . "\n";
    $users = App\Models\User::role('Teknisi')->where(function ($q) use ($names) {
        foreach ($names as $name) {
            $q->orWhereRaw('LOWER(name) = ?', [strtolower($name)]);
        }
    })->get();
    echo 'Matched: ' . implode(', ', $users->pluck('name')->all()) . "\n";
} else {
    echo "No schedule for current week\n";
}
