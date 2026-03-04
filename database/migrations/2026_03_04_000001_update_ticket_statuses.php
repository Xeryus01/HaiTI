<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // remap legacy statuses to the new set
        DB::table('tickets')->whereIn('status', ['ASSIGNED', 'IN_PROGRESS'])->update([
            'status' => Ticket::STATUS_ASSIGNED_DETECT,
        ]);

        DB::table('tickets')->where('status', 'RESOLVED')->update([
            'status' => Ticket::STATUS_SOLVED_WITH_NOTES,
        ]);

        DB::table('tickets')->where('status', 'CLOSED')->update([
            'status' => Ticket::STATUS_SOLVED,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tickets')->where('status', Ticket::STATUS_ASSIGNED_DETECT)->update([
            'status' => 'ASSIGNED',
        ]);

        DB::table('tickets')->where('status', Ticket::STATUS_SOLVED_WITH_NOTES)->update([
            'status' => 'RESOLVED',
        ]);

        DB::table('tickets')->where('status', Ticket::STATUS_SOLVED)->update([
            'status' => 'CLOSED',
        ]);
    }
};
