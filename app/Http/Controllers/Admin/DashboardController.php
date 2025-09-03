<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use App\Models\PaketLaundry;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        
        // Data statistik utama - ADMIN lihat SEMUA cabang
        $data = [
            'total_pendapatan_hari_ini' => Transaksi::whereDate('created_at', $today)
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),
            'total_piutang' => Transaksi::where('status_pembayaran', 'belum lunas')
                ->sum('total_harga'),
            'total_pengeluaran_hari_ini' => Pengeluaran::whereDate('tanggal', $today)
                ->sum('jumlah'),
            'transaksi_baru_hari_ini' => Transaksi::whereDate('created_at', $today)->count(),
            'pendapatan_minggu_ini' => Transaksi::whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga'),
            'pengeluaran_minggu_ini' => Pengeluaran::whereBetween('tanggal', [$weekStart, $weekEnd])
                ->sum('jumlah'),
        ];

        // Data untuk chart pendapatan 7 hari terakhir
        $pendapatanHarian = [];
        $labelsHarian = [];
        
        $hariIndonesia = [
            'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa',
            'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'
        ];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $pendapatan = Transaksi::whereDate('created_at', $date)
                ->where('status_pembayaran', 'lunas')
                ->sum('total_harga');
            
            $pendapatanHarian[] = $pendapatan;
            $hariInggris = $date->format('D');
            $labelsHarian[] = $hariIndonesia[$hariInggris] ?? $hariInggris;
        }

        $data['pendapatan_harian'] = $pendapatanHarian;
        $data['labels_harian'] = $labelsHarian;

        // Data untuk chart paket terpopuler
        $paketPopuler = PaketLaundry::withCount(['transaksiDetails as jumlah_pemakaian' => function($query) {
                $query->whereHas('transaksi', function($q) {
                    $q->where('status_pembayaran', 'lunas');
                });
            }])
            ->having('jumlah_pemakaian', '>', 0)
            ->orderBy('jumlah_pemakaian', 'desc')
            ->take(5)
            ->get();

        // Data transaksi terbaru
        $data['transaksi_terbaru'] = Transaksi::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $viewData = array_merge($data, [
            'paket_populer' => $paketPopuler,
            'labels_paket' => $paketPopuler->pluck('nama_paket'),
            'data_paket' => $paketPopuler->pluck('jumlah_pemakaian')
        ]);

        return view('admin.dashboard', $viewData);
    }
}