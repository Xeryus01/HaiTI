<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('team_name', 100)->nullable()->after('purpose');
            $table->unsignedSmallInteger('participants_count')->default(1)->after('team_name');
            $table->boolean('operator_needed')->default(false)->after('participants_count');
            $table->boolean('breakroom_needed')->default(false)->after('operator_needed');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['team_name', 'participants_count', 'operator_needed', 'breakroom_needed']);
        });
    }
};
