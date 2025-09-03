<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $userBranch = auth()->user()->branch;
        
        $baseQuery = Transaksi::query();
        
        if (auth()->user()->isKasir() && $userBranch) {
            $baseQuery->where('branch', $userBranch);
        }
        
        $data = [
            'antrian' => (clone $baseQuery)->where('status_cucian', 'antrian')->count(),
            'proses' => (clone $baseQuery)->whereIn('status_cucian', ['proses cuci', 'proses kering', 'proses setrika'])->count(),
            'siap_diambil' => (clone $baseQuery)->where('status_cucian', 'siap diambil')->count(),
            'selesai' => (clone $baseQuery)->where('status_cucian', 'selesai')->count(),
            'transaksi_terbaru' => (clone $baseQuery)->with('pelanggan')->latest()->take(5)->get(),
            'transaksi_hari_ini' => (clone $baseQuery)->whereDate('created_at', $today)->get() // Tambahkan ini
        ];
        
        return view('kasir.dashboard', $data);
    }
}