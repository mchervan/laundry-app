<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai filter dari request
        $cabang = $request->input('cabang', 'all');
        $search = $request->input('search', '');
        
        // Query dasar
        $query = Pelanggan::orderBy('nama');
        
        // Filter berdasarkan cabang
        if ($cabang !== 'all') {
            $query->where('branch', $cabang);
        }
        
        // Filter berdasarkan pencarian
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('no_hp', 'like', "%$search%")
                  ->orWhere('alamat', 'like', "%$search%");
            });
        }
        
        // Pagination 10 data per halaman
        $pelanggans = $query->paginate(10);
        
        // Ambil daftar cabang untuk dropdown filter
        // Gunakan query yang aman jika kolom branch belum ada
        try {
            $branches = Pelanggan::select('branch')
                ->whereNotNull('branch')
                ->distinct()
                ->pluck('branch');
        } catch (\Exception $e) {
            // Fallback jika kolom branch belum ada
            $branches = collect(['Pusat']);
        }
        
        return view('admin.manajemen.pelanggan', compact('pelanggans', 'branches', 'cabang', 'search'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
        ]);
        
        $pelanggan = Pelanggan::findOrFail($id);
        
        // Update data
        $data = [
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ];
        
        // Tambahkan branch jika kolomnya ada
        if (Schema::hasColumn('pelanggans', 'branch') && $request->has('branch')) {
            $data['branch'] = $request->branch;
        }
        
        $pelanggan->update($data);
        
        return redirect()->back()->with('success', 'Pelanggan berhasil diperbarui');
    }
    
    public function hapus($id)
    {
        Pelanggan::destroy($id);
        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus');
    }
}