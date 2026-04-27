<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('requester_id')->constrained('users');
            $table->foreignId('approver_id')->nullable()->constrained('users');
            $table->string('room_name', 120);
            $table->string('purpose', 200);
            $table->string('team_name', 100)->nullable();
            $table->unsignedSmallInteger('participants_count')->default(1);
            $table->boolean('operator_needed')->default(false);
            $table->boolean('breakroom_needed')->default(false);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('status', 30)->default('PENDING');
            $table->string('zoom_link', 255)->nullable();
            $table->string('zoom_record_link', 255)->nullable();
            $table->text('notes')->nullable();
            $table->string('nota_dinas_path', 255)->nullable();
            $table->timestamps();

            $table->index(['room_name', 'start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
