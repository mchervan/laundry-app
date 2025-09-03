@extends('layouts.kasir')

@section('title', 'Data Pelanggan')

@section('kasir-content')
<div class="container-fluid">
    <h1 class="mt-4">Data Pelanggan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Pelanggan</li>
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
                    Daftar Pelanggan
                    @if(auth()->user()->branch)
                        <span class="badge bg-info">Cabang: {{ auth()->user()->branch }}</span>
                    @endif
                </div>
                <a href="{{ route('kasir.pelanggan.tambah') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Pelanggan
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($pelanggans->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Cabang</th>
                            <th>Jumlah Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggans as $pelanggan)
                        <tr>
                            <td>{{ $pelanggan->nama }}</td>
                            <td>{{ $pelanggan->no_hp }}</td>
                            <td>{{ $pelanggan->alamat ?? '-' }}</td>
                            <td>
                                @if($pelanggan->branch)
                                    <span class="badge bg-success">{{ $pelanggan->branch }}</span>
                                @else
                                    <span class="badge bg-danger">Belum diatur</span>
                                @endif
                            </td>
                            <td>{{ $pelanggan->transaksis->count() }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> 
                @if(auth()->user()->branch)
                    Belum ada data pelanggan untuk cabang {{ auth()->user()->branch }}.
                @else
                    Belum ada data pelanggan.
                @endif
                <a href="{{ route('kasir.pelanggan.tambah') }}" class="alert-link">Klik di sini untuk menambah pelanggan baru</a>.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection