<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('piket_schedules', function (Blueprint $table) {            // Drop unique constraint first
            $table->dropUnique(['month', 'year']);
                        // Drop old columns
            $table->dropColumn(['month', 'year', 'lantai_1', 'lantai_2', 'tu']);
            
            // Add new columns for weekly schedule
            $table->date('week_start_date')->nullable(); // Monday of the week
            $table->string('technician_1')->nullable();
            $table->string('technician_2')->nullable();
            $table->string('technician_3')->nullable();
            
            // Unique constraint for week_start_date
            $table->unique('week_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piket_schedules', function (Blueprint $table) {
            // Drop new unique constraint
            $table->dropUnique(['week_start_date']);
            
            // Drop new columns
            $table->dropColumn(['week_start_date', 'technician_1', 'technician_2', 'technician_3']);
            
            // Add back old columns
            $table->integer('month');
            $table->integer('year')->default(2026);
            $table->string('lantai_1')->nullable();
            $table->string('lantai_2')->nullable();
            $table->string('tu')->nullable();
            
            // Unique constraint
            $table->unique(['month', 'year']);
        });
    }
};
