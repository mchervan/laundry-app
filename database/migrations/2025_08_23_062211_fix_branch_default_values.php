<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus default value 'Pusat' dari users table
        DB::statement("ALTER TABLE users MODIFY branch VARCHAR(255) DEFAULT NULL");
        
        // Hapus default value 'Pusat' dari transaksis table
        DB::statement("ALTER TABLE transaksis MODIFY branch VARCHAR(255) DEFAULT NULL");
        
        // Update existing records yang masih 'Pusat' menjadi NULL
        DB::table('users')->where('branch', 'Pusat')->update(['branch' => null]);
        DB::table('transaksis')->where('branch', 'Pusat')->update(['branch' => null]);
    }

    public function down(): void
    {
        // Kembalikan default value jika rollback
        DB::statement("ALTER TABLE users MODIFY branch VARCHAR(255) DEFAULT 'Pusat'");
        DB::statement("ALTER TABLE transaksis MODIFY branch VARCHAR(255) DEFAULT 'Pusat'");
        
        DB::table('users')->whereNull('branch')->update(['branch' => 'Pusat']);
        DB::table('transaksis')->whereNull('branch')->update(['branch' => 'Pusat']);
    }
};