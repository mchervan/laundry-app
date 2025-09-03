@extends('layouts.admin')

@section('title', 'Kelola Pelanggan')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Kelola Pelanggan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kelola Pelanggan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Daftar Pelanggan Semua Cabang
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.manajemen.pelanggan') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="cabang" class="form-label">Filter Cabang</label>
                        <select class="form-select" id="cabang" name="cabang">
                            <option value="all" {{ $cabang == 'all' ? 'selected' : '' }}>Semua Cabang</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch }}" {{ $cabang == $branch ? 'selected' : '' }}>
                                    {{ $branch }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Cari nama, no HP, atau alamat..." value="{{ $search }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.manajemen.pelanggan') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-sync me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>No</th>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Cabang</th>
                            <th>Jumlah Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans as $index => $pelanggan)
                        <tr>
                            <td>{{ $pelanggans->firstItem() + $index }}</td>
                            <td>{{ $pelanggan->nama }}</td>
                            <td>{{ $pelanggan->no_hp }}</td>
                            <td>{{ $pelanggan->alamat ?? '-' }}</td>
                            <td>{{ $pelanggan->branch ?? 'Pusat' }}</td>
                            <td class="text-center">{{ $pelanggan->transaksis_count ?? $pelanggan->transaksis->count() }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPelangganModal{{ $pelanggan->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.manajemen.pelanggan.hapus', $pelanggan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editPelangganModal{{ $pelanggan->id }}" tabindex="-1" aria-labelledby="editPelangganModalLabel{{ $pelanggan->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editPelangganModalLabel{{ $pelanggan->id }}">Edit Pelanggan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.manajemen.pelanggan.update', $pelanggan->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $pelanggan->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="no_hp" class="form-label">No. HP</label>
                                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $pelanggan->no_hp }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ $pelanggan->alamat }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="branch" class="form-label">Cabang</label>
                                                <select class="form-select" id="branch" name="branch" required>
                                                    <option value="Pusat" {{ $pelanggan->branch == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch }}" {{ $pelanggan->branch == $branch ? 'selected' : '' }}>
                                                            {{ $branch }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada data pelanggan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pelanggans->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $pelanggans->firstItem() }} hingga {{ $pelanggans->lastItem() }} dari {{ $pelanggans->total() }} entri
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        @if($pelanggans->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo; Sebelumnya</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $pelanggans->previousPageUrl() }}" rel="prev">&laquo; Sebelumnya</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($pelanggans->links()->elements[0] as $page => $url)
                            @if($page == $pelanggans->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($pelanggans->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $pelanggans->nextPageUrl() }}" rel="next">Selanjutnya &raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Selanjutnya &raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @else
            <div class="text-center text-muted mt-3">
                Menampilkan {{ $pelanggans->count() }} dari {{ $pelanggans->total() }} entri
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .pagination {
        margin-bottom: 0;
    }
    
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .page-link {
        color: #0d6efd;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
    }
    
    .page-link:hover {
        color: #0a58ca;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    .bg-dark {
        background-color: #212529 !important;
    }
    
    .table thead th {
        border-bottom: 2px solid #000000;
        font-weight: 600;
        padding: 12px;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
        padding: 12px;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endsection