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
        Schema::table('reservations', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['requester_id']);
            // Add the new foreign key constraint with cascade on delete
            $table->foreign('requester_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Drop the cascade foreign key
            $table->dropForeign(['requester_id']);
            // Restore the original foreign key (restrict on delete)
            $table->foreign('requester_id')
                ->references('id')->on('users');
        });
    }
};
