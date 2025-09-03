<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaksi_id', 'paket_laundry_id', 'jumlah', 'harga', 'subtotal'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketLaundry::class, 'paket_laundry_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->jumlah <= 0) {
                throw new \Exception('Jumlah harus lebih dari 0');
            }
            
            // Hitung subtotal otomatis
            $paket = PaketLaundry::find($model->paket_laundry_id);
            if ($paket) {
                $model->harga = $paket->harga;
                $model->subtotal = $paket->harga * $model->jumlah;
            }
        });

        static::updating(function ($model) {
            if ($model->jumlah <= 0) {
                throw new \Exception('Jumlah harus lebih dari 0');
            }
            
            // Update subtotal jika jumlah atau harga berubah
            $model->subtotal = $model->harga * $model->jumlah;
        });
    }
}