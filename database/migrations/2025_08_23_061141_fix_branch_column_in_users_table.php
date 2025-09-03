<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if column exists first
        if (Schema::hasColumn('users', 'branch')) {
            // Modify the existing column if needed
            Schema::table('users', function (Blueprint $table) {
                $table->string('branch')->default('Pusat')->change();
            });
        } else {
            // Add the column if it doesn't exist
            Schema::table('users', function (Blueprint $table) {
                $table->string('branch')->default('Pusat')->after('role');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('branch');
        });
    }
};