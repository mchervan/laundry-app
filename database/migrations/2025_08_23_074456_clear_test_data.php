<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus semua data transaksi dan detailnya
        DB::table('transaksi_details')->delete();
        DB::table('transaksis')->delete();
        
        // Hapus semua user kecuali admin utama
        DB::table('users')->where('email', '!=', 'admin@laundry.com')->delete();
        
        // Hapus pelanggan dummy jika ada
        DB::table('pelanggans')->delete();
        
        // Reset auto increment
        DB::statement('ALTER TABLE transaksi_details AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE transaksis AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE pelanggans AUTO_INCREMENT = 1');
    }

    public function down(): void
    {
        // Rollback tidak diperlukan untuk operasi hapus data
    }
};