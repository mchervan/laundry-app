@extends('layouts.admin')

@section('title', 'Kelola Paket Laundry')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Kelola Paket Laundry</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kelola Paket</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Daftar Paket Laundry
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPaketModal">
                    <i class="fas fa-plus me-1"></i> Tambah Paket
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Paket</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pakets as $paket)
                        <tr>
                            <td>{{ $paket->nama_paket }}</td>
                            <td>{{ $paket->deskripsi }}</td>
                            <td>Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($paket->satuan) }}</td>
                            <td>
                                @if($paket->aktif)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPaketModal{{ $paket->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.manajemen.paket.toggle', $paket->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($paket->aktif)
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i> Nonaktifkan
                                    </button>
                                    @else
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Aktifkan
                                    </button>
                                    @endif
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editPaketModal{{ $paket->id }}" tabindex="-1" aria-labelledby="editPaketModalLabel{{ $paket->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editPaketModalLabel{{ $paket->id }}">Edit Paket {{ $paket->nama_paket }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.manajemen.paket.update', $paket->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama_paket" class="form-label">Nama Paket</label>
                                                <input type="text" class="form-control" id="nama_paket" name="nama_paket" value="{{ $paket->nama_paket }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ $paket->deskripsi }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="harga" class="form-label">Harga</label>
                                                <input type="number" class="form-control" id="harga" name="harga" value="{{ $paket->harga }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="satuan" class="form-label">Satuan</label>
                                                <select class="form-select" id="satuan" name="satuan" required>
                                                    <option value="kg" {{ $paket->satuan == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                                    <option value="pcs" {{ $paket->satuan == 'pcs' ? 'selected' : '' }}>Piece (pcs)</option>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahPaketModal" tabindex="-1" aria-labelledby="tambahPaketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPaketModalLabel">Tambah Paket Laundry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.manajemen.paket.simpan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_paket" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="nama_paket" name="nama_paket" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <select class="form-select" id="satuan" name="satuan" required>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="pcs">Piece (pcs)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection