<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaketLaundry;
use App\Models\User;

class ManajemenController extends Controller
{
    // Paket Laundry
    public function paket()
    {
        $pakets = PaketLaundry::all();
        return view('admin.manajemen.paket', compact('pakets'));
    }
    
    public function simpanPaket(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required',
            'harga' => 'required|numeric',
            'satuan' => 'required|in:kg,pcs',
        ]);
        
        PaketLaundry::create($request->all());
        
        return redirect()->back()->with('success', 'Paket berhasil ditambahkan');
    }
    
    public function updatePaket(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required',
            'harga' => 'required|numeric',
            'satuan' => 'required|in:kg,pcs',
        ]);
        
        $paket = PaketLaundry::findOrFail($id);
        $paket->update($request->all());
        
        return redirect()->back()->with('success', 'Paket berhasil diperbarui');
    }
    
    public function togglePaket($id)
    {
        $paket = PaketLaundry::findOrFail($id);
        $paket->aktif = !$paket->aktif;
        $paket->save();
        
        return redirect()->back()->with('success', 'Status paket berhasil diubah');
    }
    
    // Karyawan
    public function karyawan()
    {
        $karyawans = User::where('role', 'kasir')->get();
        return view('admin.manajemen.karyawan', compact('karyawans'));
    }
    
    public function simpanKaryawan(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'kasir',
        ]);
        
        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan');
    }
    
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = bcrypt('password123');
        $user->save();
        
        return redirect()->back()->with('success', 'Password berhasil direset ke "password123"');
    }
    
    public function hapusKaryawan($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'Karyawan berhasil dihapus');
    }
}