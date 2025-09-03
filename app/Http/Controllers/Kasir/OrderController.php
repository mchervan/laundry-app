<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Pelanggan;
use App\Models\PaketLaundry;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function baru()
    {
        $pakets = PaketLaundry::all();
        return view('kasir.order.baru', compact('pakets'));
    }

    public function cariPelanggan(Request $request)
    {
        $keyword = $request->keyword;
        $userBranch = auth()->user()->branch;
        
        $query = Pelanggan::where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('no_hp', 'like', "%{$keyword}%");
            });
        
        if ($userBranch) {
            $query->where('branch', $userBranch);
        }
        
        $pelanggans = $query->limit(10)->get();
            
        return response()->json($pelanggans);
    }

    public function simpanPelanggan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15|unique:pelanggans,no_hp',
            'alamat' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pelanggan = Pelanggan::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'branch' => auth()->user()->branch
        ]);

        return response()->json([
            'success' => true,
            'id' => $pelanggan->id,
            'nama' => $pelanggan->nama,
            'no_hp' => $pelanggan->no_hp,
            'alamat' => $pelanggan->alamat
        ]);
    }

    public function simpanTransaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'status_pembayaran' => 'required|in:lunas,belum lunas',
            'catatan' => 'nullable|string|max:500',
            'pakets' => 'required|array|min:1',
            'pakets.*.id' => 'required|exists:paket_laundries,id',
            'pakets.*.jumlah' => 'required|numeric|min:0.1',
            'pakets.*.harga' => 'required|numeric|min:0'
        ], [
            'pelanggan_id.required' => 'Pelanggan harus dipilih',
            'pelanggan_id.exists' => 'Pelanggan tidak valid',
            'status_pembayaran.required' => 'Status pembayaran harus dipilih',
            'status_pembayaran.in' => 'Status pembayaran tidak valid',
            'pakets.required' => 'Minimal satu paket harus dipilih',
            'pakets.min' => 'Minimal satu paket harus dipilih',
            'pakets.*.id.required' => 'ID paket harus ada',
            'pakets.*.id.exists' => 'Paket tidak valid',
            'pakets.*.jumlah.required' => 'Jumlah paket harus diisi',
            'pakets.*.jumlah.min' => 'Jumlah paket minimal 0.1',
            'pakets.*.harga.required' => 'Harga paket harus ada',
            'pakets.*.harga.min' => 'Harga paket tidak valid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . Str::upper(Str::random(6));

            $totalHarga = 0;
            foreach ($request->pakets as $paket) {
                $totalHarga += $paket['harga'] * $paket['jumlah'];
            }

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'pelanggan_id' => $request->pelanggan_id,
                'user_id' => auth()->id(),
                'total_harga' => $totalHarga,
                'status_pembayaran' => $request->status_pembayaran,
                'status_cucian' => 'antrian',
                'catatan' => $request->catatan,
                'tanggal_masuk' => now(),
                'branch' => auth()->user()->branch
            ]);

            foreach ($request->pakets as $paketData) {
                $paket = PaketLaundry::find($paketData['id']);
                
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'paket_laundry_id' => $paketData['id'],
                    'jumlah' => $paketData['jumlah'],
                    'harga_satuan' => $paketData['harga'],
                    'subtotal' => $paketData['harga'] * $paketData['jumlah'],
                    'jenis_paket' => $paket->jenis,
                    'nama_paket' => $paket->nama_paket
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'kode_transaksi' => $kodeTransaksi,
                'transaksi_id' => $transaksi->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        $userBranch = auth()->user()->branch;
        
        $query = Transaksi::with('pelanggan')->orderBy('created_at', 'desc');
        
        if (auth()->user()->isKasir() && $userBranch) {
            $query->where('branch', $userBranch);
        }
        
        $transaksis = $query->get();
            
        return view('kasir.order.list', compact('transaksis'));
    }

    public function detail($id)
    {
        $userBranch = auth()->user()->branch;
        
        $query = Transaksi::with(['pelanggan', 'details.paket']);
        
        if (auth()->user()->isKasir() && $userBranch) {
            $query->where('branch', $userBranch);
        }
        
        $transaksi = $query->findOrFail($id);
                
        return view('kasir.order.detail', compact('transaksi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_cucian' => 'required|in:antrian,proses cuci,proses kering,proses setrika,siap diambil,selesai',
        ]);
        
        $userBranch = auth()->user()->branch;
        
        $query = Transaksi::query();
        
        if (auth()->user()->isKasir() && $userBranch) {
            $query->where('branch', $userBranch);
        }
        
        $transaksi = $query->findOrFail($id);
            
        $transaksi->update([
            'status_cucian' => $request->status_cucian,
        ]);
        
        if ($request->status_cucian == 'siap diambil') {
            $transaksi->update(['tanggal_selesai' => now()]);
        }
        
        return redirect()->back()->with('success', 'Status cucian berhasil diperbarui');
    }
}