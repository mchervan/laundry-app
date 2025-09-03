<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi', 'pelanggan_id', 'tanggal_masuk', 'tanggal_selesai',
        'status_pembayaran', 'status_cucian', 'total_harga', 'catatan', 'branch'
    ];

    // Tambahkan casting untuk tanggal
    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            $transaksi->kode_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            if (auth()->check()) {
                $transaksi->branch = auth()->user()->branch;
            }
        });
    }

    public function scopeByCabang($query, $cabang)
    {
        if ($cabang && $cabang !== 'all') {
            return $query->where('branch', $cabang);
        }
        return $query;
    }

    public function scopeForKasir($query, $userBranch)
    {
        if ($userBranch) {
            return $query->where('branch', $userBranch);
        }
        return $query;
    }
}