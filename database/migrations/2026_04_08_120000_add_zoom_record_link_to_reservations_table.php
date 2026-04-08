<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('reservations', 'zoom_record_link')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('zoom_record_link', 255)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'zoom_record_link')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropColumn('zoom_record_link');
            });
        }
    }
};
