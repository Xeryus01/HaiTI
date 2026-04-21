<?php

namespace Database\Seeders;

use App\Models\CodeSequence;
use Illuminate\Database\Seeder;

class CodeSequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create code sequences for recent dates
        $today = now()->startOfDay();

        for ($i = 30; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);

            CodeSequence::updateOrCreate(
                ['date' => $date],
                [
                    'ticket_count' => rand(0, 15),
                    'reservation_count' => rand(0, 10),
                ]
            );
        }

        // Ensure today exists
        if (!CodeSequence::whereDate('date', $today)->exists()) {
            CodeSequence::create([
                'date' => $today,
                'ticket_count' => 0,
                'reservation_count' => 0,
            ]);
        }
    }
}
