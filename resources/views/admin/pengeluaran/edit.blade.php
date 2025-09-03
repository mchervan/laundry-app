@extends('layouts.admin')

@section('title', 'Edit Pengeluaran')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Edit Pengeluaran</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.laporan.pengeluaran') }}">Laporan Pengeluaran</a></li>
        <li class="breadcrumb-item active">Edit Pengeluaran</li>
    </ol>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <i class="fas fa-edit me-1"></i>
            Edit Data Pengeluaran
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengeluaran.update', $pengeluaran->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori Pengeluaran *</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategories as $kategori)
                                    <option value="{{ $kategori }}" 
                                        {{ $pengeluaran->kategori == $kategori ? 'selected' : '' }}>
                                        {{ $kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="branch" class="form-label">Cabang *</label>
                            <select class="form-select" id="branch" name="branch" required>
                                <option value="">Pilih Cabang</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch }}" 
                                        {{ $pengeluaran->user->branch == $branch ? 'selected' : '' }}>
                                        {{ $branch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Pengeluaran *</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $pengeluaran->deskripsi }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Pengeluaran (Rp) *</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                   min="1000" step="500" value="{{ $pengeluaran->jumlah }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Pengeluaran *</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                   value="{{ $pengeluaran->tanggal->format('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Pengeluaran
                    </button>
                    <a href="{{ route('admin.laporan.pengeluaran') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Laporan
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection