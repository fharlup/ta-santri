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
           Schema::table('users', function (Blueprint $table) {

        // EMAIL
        if (!Schema::hasColumn('users', 'email')) {
            $table->string('email')->nullable()->after('id');
        } else {
            $table->string('email')->nullable()->change();
        }

        // USERNAME
        if (!Schema::hasColumn('users', 'username')) {
            $table->string('username')->unique()->after('email');
        }

        // ROLE
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->after('password');
        }
           });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
