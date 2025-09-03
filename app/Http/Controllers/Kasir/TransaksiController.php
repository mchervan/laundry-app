<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function lunas($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update(['status_pembayaran' => 'lunas']);
        
        return redirect()->back()->with('success', 'Status pembayaran berhasil diubah menjadi Lunas');
    }
    
    public function selesai($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->status_pembayaran != 'lunas') {
            return redirect()->back()->with('error', 'Transaksi belum lunas, tidak bisa diselesaikan');
        }
        
        $transaksi->update(['status_cucian' => 'selesai']);
        
        return redirect()->back()->with('success', 'Status cucian berhasil diubah menjadi Selesai');
    }
}