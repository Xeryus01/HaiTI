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
        Schema::create('piket_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('month'); // 1-12
            $table->integer('year')->default(2026);
            
            // Lantai 1, Lantai 2, TU
            $table->string('lantai_1')->nullable();
            $table->string('lantai_2')->nullable();
            $table->string('tu')->nullable();
            
            $table->timestamps();
            
            // Unique constraint per month-year combination
            $table->unique(['month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piket_schedules');
    }
};
