<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('pelanggan_id')->constrained()->onDelete('cascade');
            $table->dateTime('tanggal_masuk');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->enum('status_pembayaran', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->enum('status_cucian', [
                'antrian', 
                'proses cuci', 
                'proses kering', 
                'proses setrika', 
                'siap diambil', 
                'selesai'
            ])->default('antrian');
            $table->decimal('total_harga', 10, 2);
            $table->text('catatan')->nullable();
            $table->string('branch')->default('Pusat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};