<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $mapping = [
            'FAIR' => 'LIGHT',
            'POOR' => 'HEAVY',
            'DAMAGED' => 'HEAVY',
            'RUSAK' => 'HEAVY',
            'RUSAK_RINGAN' => 'LIGHT',
            'RUSAK BERAT' => 'HEAVY',
            'RUSAK_BERAT' => 'HEAVY',
            'LIGHT_DAMAGE' => 'LIGHT',
            'HEAVY_DAMAGE' => 'HEAVY',
        ];

        foreach ($mapping as $from => $to) {
            DB::table('assets')->where('condition', $from)->update(['condition' => $to]);
            DB::table('asset_maintenances')->where('condition_before', $from)->update(['condition_before' => $to]);
            DB::table('asset_maintenances')->where('condition_after', $from)->update(['condition_after' => $to]);
        }
    }

    public function down(): void
    {
        // No reverse mapping to avoid accidental data loss.
    }
};
