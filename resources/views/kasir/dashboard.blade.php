@extends('layouts.kasir')

@section('title', 'Dashboard Kasir')

@section('kasir-content')
<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="row stats-grid">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('kasir.order.list') }}?status=antrian" data-title="Antrian Cucian">
                <div class="icon bg-secondary">
                    <i class="fas fa-list-ol"></i>
                </div>
                <h2>{{ $antrian }}</h2>
                <p>Antrian Cucian</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Antrian
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('kasir.order.list') }}?status=proses" data-title="Order Dalam Proses">
                <div class="icon bg-info">
                    <i class="fas fa-spinner"></i>
                </div>
                <h2>{{ $proses }}</h2>
                <p>Dalam Proses</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Proses
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('kasir.order.list') }}?status=siap_diambil" data-title="Order Siap Diambil">
                <div class="icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>{{ $siap_diambil }}</h2>
                <p>Siap Diambil</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Order Siap
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('kasir.order.list') }}?status=selesai" data-title="Order Selesai">
                <div class="icon bg-dark">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h2>{{ $selesai }}</h2>
                <p>Selesai</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Selesai
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 mt-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Transaksi Terbaru
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
                        @forelse($transaksi_terbaru as $transaksi)
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-bolt me-1"></i>
                    Aksi Cepat
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="{{ route('kasir.order.baru') }}" class="btn btn-primary me-md-2">
                            <i class="fas fa-plus-circle me-1"></i> Input Order Baru
                        </a>
                        <a href="{{ route('kasir.pelanggan.list') }}" class="btn btn-success me-md-2">
                            <i class="fas fa-users me-1"></i> Kelola Pelanggan
                        </a>
                        <a href="{{ route('kasir.order.list') }}" class="btn btn-info">
                            <i class="fas fa-list me-1"></i> Lihat Semua Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Statistik Order
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" width="100%" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var ctx = document.getElementById("orderStatusChart");
    var orderStatusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Antrian', 'Dalam Proses', 'Siap Diambil', 'Selesai'],
            datasets: [{
                data: [{{ $antrian }}, {{ $proses }}, {{ $siap_diambil }}, {{ $selesai }}],
                backgroundColor: [
                    'rgba(108, 117, 125, 0.8)',
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(52, 58, 64, 0.8)'
                ],
                borderColor: [
                    'rgba(108, 117, 125, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(52, 58, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    $(document).ready(function() {
        $('.clickable-card').on('click', function() {
            const url = $(this).data('url');
            const title = $(this).data('title');
            
            $(this).addClass('card-clicked');
            setTimeout(() => {
                $(this).removeClass('card-clicked');
                window.location.href = url;
            }, 300);
        });

        $('.clickable-card').hover(
            function() {
                $(this).addClass('card-hover');
            },
            function() {
                $(this).removeClass('card-hover');
            }
        );
    });
</script>
@endpush
@endsection