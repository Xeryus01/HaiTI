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
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('status', 30)->default('PENDING');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['room_name', 'start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
