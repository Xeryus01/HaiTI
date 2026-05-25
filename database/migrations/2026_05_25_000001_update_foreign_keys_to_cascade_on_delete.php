<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Attachments
        DB::statement('ALTER TABLE attachments DROP FOREIGN KEY attachments_uploader_id_foreign');
        Schema::table('attachments', function (Blueprint $table) {
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');
        });
        // Logs
        DB::statement('ALTER TABLE logs DROP FOREIGN KEY logs_actor_id_foreign');
        Schema::table('logs', function (Blueprint $table) {
            $table->foreign('actor_id')->references('id')->on('users')->onDelete('cascade');
        });
        // Tickets
        DB::statement('ALTER TABLE tickets DROP FOREIGN KEY tickets_requester_id_foreign');
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
        });
        if (Schema::hasColumn('tickets', 'assignee_id')) {
            DB::statement('ALTER TABLE tickets DROP FOREIGN KEY tickets_assignee_id_foreign');
            Schema::table('tickets', function (Blueprint $table) {
                $table->foreign('assignee_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Attachments
        DB::statement('ALTER TABLE attachments DROP FOREIGN KEY attachments_uploader_id_foreign');
        Schema::table('attachments', function (Blueprint $table) {
            $table->foreign('uploader_id')->references('id')->on('users');
        });
        // Logs
        DB::statement('ALTER TABLE logs DROP FOREIGN KEY logs_actor_id_foreign');
        Schema::table('logs', function (Blueprint $table) {
            $table->foreign('actor_id')->references('id')->on('users');
        });
        // Tickets
        DB::statement('ALTER TABLE tickets DROP FOREIGN KEY tickets_requester_id_foreign');
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign('requester_id')->references('id')->on('users');
        });
        if (Schema::hasColumn('tickets', 'assignee_id')) {
            DB::statement('ALTER TABLE tickets DROP FOREIGN KEY tickets_assignee_id_foreign');
            Schema::table('tickets', function (Blueprint $table) {
                $table->foreign('assignee_id')->references('id')->on('users');
            });
        }
    }
};
