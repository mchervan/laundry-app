<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        $userBranch = auth()->user()->branch;
        
        // Jika kasir memiliki cabang, filter berdasarkan cabang
        if ($userBranch) {
            $pelanggans = Pelanggan::where('branch', $userBranch)
                            ->orderBy('nama')
                            ->get();
        } else {
            // Jika kasir tidak memiliki cabang, tampilkan semua
            $pelanggans = Pelanggan::orderBy('nama')->get();
        }
        
        return view('kasir.pelanggan.list', compact('pelanggans'));
    }
    
    public function tambah()
    {
        return view('kasir.pelanggan.tambah');
    }
    
    public function simpan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
        ]);
        
        // Pastikan branch selalu terisi
        $branch = auth()->user()->branch ?? 'Pusat';
        
        Pelanggan::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'branch' => $branch
        ]);
        
        return redirect()->route('kasir.pelanggan.list')->with('success', 'Pelanggan berhasil ditambahkan');
    }
}