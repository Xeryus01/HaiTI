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
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->enum('type', ['PREVENTIVE', 'CORRECTIVE', 'INSPECTION']);
            $table->date('maintenance_date');
            $table->text('description');
            $table->text('findings')->nullable();
            $table->text('actions_taken')->nullable();
            $table->string('status')->default('COMPLETED');
            $table->string('condition_before')->nullable();
            $table->string('condition_after')->nullable();
            $table->foreignId('performed_by_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('next_maintenance_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
