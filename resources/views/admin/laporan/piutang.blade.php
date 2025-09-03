@extends('layouts.admin')

@section('title', 'Laporan Piutang')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Laporan Piutang</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Piutang</li>
    </ol>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Laporan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.piutang') }}">
                <div class="row">
                    <div class="col-md-4">
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
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <a href="{{ route('admin.laporan.piutang') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-sync me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Daftar Transaksi Belum Lunas {{ $cabang != 'all' ? "- Cabang $cabang" : '' }}
            </div>
            <div>
                <span class="badge bg-danger">Total Piutang: Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($transaksis->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-black text-black">
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>No. HP</th>
                            <th>Cabang</th>
                            <th>Total Harga</th>
                            <th>Status Cucian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksi->kode_transaksi }}</td>
                            <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaksi->pelanggan->nama }}</td>
                            <td>{{ $transaksi->pelanggan->no_hp }}</td>
                            <td>{{ $transaksi->branch }}</td>
                            <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($transaksi->status_cucian == 'antrian')
                                    <span class="badge bg-secondary">Antrian</span>
                                @elseif($transaksi->status_cucian == 'proses cuci')
                                    <span class="badge bg-info">Proses Cuci</span>
                                @elseif($transaksi->status_cucian == 'proses kering')
                                    <span class="badge bg-warning">Proses Kering</span>
                                @elseif($transaksi->status_cucian == 'proses setrika')
                                    <span class="badge bg-primary">Proses Setrika</span>
                                @elseif($transaksi->status_cucian == 'siap diambil')
                                    <span class="badge bg-success">Siap Diambil</span>
                                @else
                                    <span class="badge bg-dark">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('kasir.order.detail', $transaksi->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('kasir.transaksi.lunas', $transaksi->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Tandai sebagai Lunas" onclick="return confirm('Tandai transaksi ini sebagai lunas?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-dark">
                            <th colspan="5" class="text-end">Total Piutang</th>
                            <th colspan="3">Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Tidak ada data transaksi belum lunas untuk cabang yang dipilih.
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-black {
        background-color: #000000 !important;
    }
    .table thead th {
        border-bottom: 2px solid #000000;
        font-weight: bold;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
</style>
@endsection