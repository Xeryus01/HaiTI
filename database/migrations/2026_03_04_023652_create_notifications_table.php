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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type')->default('info'); // info, warning, success, error
            $table->string('title');
            $table->text('message');
            $table->string('action_type')->nullable(); // ticket, asset, reservation
            $table->unsignedBigInteger('action_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('whatsapp_sent')->default(false);
            $table->string('whatsapp_status')->nullable(); // pending, sent, failed
            $table->text('whatsapp_response')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
