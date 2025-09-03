<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PaketLaundry;

class RealDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin/Pemilik (tetap pakai role 'admin')
        User::firstOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Admin Pemilik',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'branch' => null // Admin tidak terikat cabang
            ]
        );

        // 2. Kasir Cabang Pemuda
        User::firstOrCreate(
            ['email' => 'kasir.pemuda@laundry.com'],
            [
                'name' => 'Kasir Cabang Pemuda',
                'password' => bcrypt('kasir123'),
                'role' => 'kasir',
                'branch' => 'Pemuda'
            ]
        );

        // 3. Kasir Cabang Serayu
        User::firstOrCreate(
            ['email' => 'kasir.serayu@laundry.com'],
            [
                'name' => 'Kasir Cabang Serayu',
                'password' => bcrypt('kasir123'),
                'role' => 'kasir',
                'branch' => 'Serayu'
            ]
        );

        // 4. Kasir Cabang Setia Budi
        User::firstOrCreate(
            ['email' => 'kasir.setiabudi@laundry.com'],
            [
                'name' => 'Kasir Cabang Setia Budi',
                'password' => bcrypt('kasir123'),
                'role' => 'kasir',
                'branch' => 'Setia Budi'
            ]
        );

        // 5. Kasir Cabang Munggut
        User::firstOrCreate(
            ['email' => 'kasir.munggut@laundry.com'],
            [
                'name' => 'Kasir Cabang Munggut',
                'password' => bcrypt('kasir123'),
                'role' => 'kasir',
                'branch' => 'Munggut'
            ]
        );

        // 6. Paket-paket laundry (jika belum ada)
        $pakets = [
            [
                'nama_paket' => 'Cuci Reguler',
                'deskripsi' => 'Cuci biasa 3 hari selesai',
                'harga' => 5000,
                'satuan' => 'kg',
                'aktif' => true
            ],
            [
                'nama_paket' => 'Cuci Express',
                'deskripsi' => 'Cuci cepat 1 hari selesai', 
                'harga' => 8000,
                'satuan' => 'kg',
                'aktif' => true
            ],
            [
                'nama_paket' => 'Setrika Saja',
                'deskripsi' => 'Hanya setrika',
                'harga' => 3000,
                'satuan' => 'pcs',
                'aktif' => true
            ],
            [
                'nama_paket' => 'Dry Cleaning',
                'deskripsi' => 'Dry cleaning khusus',
                'harga' => 15000,
                'satuan' => 'pcs',
                'aktif' => true
            ]
        ];

        foreach ($pakets as $paket) {
            PaketLaundry::firstOrCreate(
                ['nama_paket' => $paket['nama_paket']],
                $paket
            );
        }
    }
}