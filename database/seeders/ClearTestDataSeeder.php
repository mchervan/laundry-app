<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Non-aktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Hapus semua data (gunakan delete() bukan truncate())
        DB::table('transaksi_details')->delete();
        DB::table('transaksis')->delete();
        DB::table('pelanggans')->delete();
        DB::table('users')->where('email', '!=', 'admin@laundry.com')->delete();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Reset auto increment
        DB::statement('ALTER TABLE transaksi_details AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE transaksis AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE pelanggans AUTO_INCREMENT = 1');
        
        echo "Data test berhasil dihapus!\n";
    }
}