@extends('layouts.kasir')

@section('title', 'Daftar Order')

@section('kasir-content')
<div class="container-fluid">
    <h1 class="mt-4">Daftar Order</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Daftar Order</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Semua Transaksi
                </div>
                <a href="{{ route('kasir.order.baru') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Order Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Status Cucian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksi->kode_transaksi }}</td>
                            <td>{{ $transaksi->pelanggan->nama }}</td>
                            <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($transaksi->status_pembayaran == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Belum Lunas</span>
                                @endif
                            </td>
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
                                <a href="{{ route('kasir.order.detail', $transaksi->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($transaksi->status_pembayaran == 'belum lunas')
                                    <form action="{{ route('kasir.transaksi.lunas', $transaksi->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Tandai sebagai Lunas">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($transaksi->status_cucian == 'siap diambil' && $transaksi->status_pembayaran == 'lunas')
                                    <form action="{{ route('kasir.transaksi.selesai', $transaksi->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" title="Tandai sebagai Selesai">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection