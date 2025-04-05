<?php

// database/migrations/2023_01_01_000010_modify_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'doctor'])->default('admin')->after('email');
            $table->unsignedBigInteger('branch_id')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('branch_id');

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['role', 'branch_id', 'is_active']);
        });
    }
};