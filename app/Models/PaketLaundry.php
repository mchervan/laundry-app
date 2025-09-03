<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketLaundry extends Model
{
    use HasFactory;

    protected $table = 'paket_laundries';

    protected $fillable = ['nama_paket', 'deskripsi', 'harga', 'satuan', 'aktif'];

    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    // Method untuk menghitung jumlah pemakaian paket
    public function getJumlahPemakaianAttribute()
    {
        return $this->transaksiDetails()
            ->whereHas('transaksi', function($query) {
                $query->where('status_pembayaran', 'lunas');
            })
            ->count();
    }
}