@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Laporan Penjualan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Penjualan</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Laporan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.penjualan') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="cabang" class="form-label">Cabang</label>
                        <select class="form-select" id="cabang" name="cabang">
                            <option value="all" {{ $cabang == 'all' ? 'selected' : '' }}>Semua Cabang</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch }}" {{ $cabang == $branch ? 'selected' : '' }}>
                                    {{ $branch }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Data Transaksi {{ $cabang != 'all' ? "- Cabang $cabang" : '' }}
            </div>
            <div>
                <a href="{{ route('admin.laporan.penjualan.export', ['format' => 'excel', 'start_date' => $start, 'end_date' => $end, 'cabang' => $cabang]) }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.laporan.penjualan.export', ['format' => 'pdf', 'start_date' => $start, 'end_date' => $end, 'cabang' => $cabang]) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($transaksis->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Cabang</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksi->kode_transaksi }}</td>
                            <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaksi->pelanggan->nama }}</td>
                            <td>{{ $transaksi->branch }}</td>
                            <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-success">Lunas</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <th colspan="2">Rp {{ number_format($total, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Tidak ada data transaksi untuk periode yang dipilih.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection