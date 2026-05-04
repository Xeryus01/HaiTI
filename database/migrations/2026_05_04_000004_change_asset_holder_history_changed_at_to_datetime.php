<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_holder_history', function (Blueprint $table) {
            $table->dateTime('changed_at')->change();
        });
    }

    public function down(): void
    {
        Schema::table('asset_holder_history', function (Blueprint $table) {
            $table->date('changed_at')->change();
        });
    }
};
