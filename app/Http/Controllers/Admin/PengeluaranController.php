<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\User;

class PengeluaranController extends Controller
{
    public function create()
    {
        $branches = User::whereNotNull('branch')->distinct()->pluck('branch');
        $kategories = [
            'Bahan Pencucian',
            'Peralatan Laundry',
            'Listrik & Air',
            'Transportasi & Delivery',
            'Gaji Karyawan',
            'Sewa Tempat',
            'Maintenance',
            'Lain-lain'
        ];
        
        return view('admin.pengeluaran.create', compact('branches', 'kategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
            'branch' => 'required|string|max:255'
        ]);

        // Dapatkan user_id berdasarkan cabang
        $user = User::where('branch', $request->branch)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Cabang tidak ditemukan!');
        }

        Pengeluaran::create([
            'user_id' => $user->id,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal
        ]);

        return redirect()->route('admin.laporan.pengeluaran')
            ->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pengeluaran = Pengeluaran::with('user')->findOrFail($id);
        $branches = User::whereNotNull('branch')->distinct()->pluck('branch');
        $kategories = [
            'Bahan Pencucian',
            'Peralatan Laundry',
            'Listrik & Air',
            'Transportasi & Delivery',
            'Gaji Karyawan',
            'Sewa Tempat',
            'Maintenance',
            'Lain-lain'
        ];
        
        return view('admin.pengeluaran.edit', compact('pengeluaran', 'branches', 'kategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:500',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal' => 'required|date',
            'branch' => 'required|string|max:255'
        ]);

        $pengeluaran = Pengeluaran::findOrFail($id);
        
        // Dapatkan user_id berdasarkan cabang
        $user = User::where('branch', $request->branch)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Cabang tidak ditemukan!');
        }

        $pengeluaran->update([
            'user_id' => $user->id,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal
        ]);

        return redirect()->route('admin.laporan.pengeluaran')
            ->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus!');
    }
}