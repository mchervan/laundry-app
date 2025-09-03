@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Pendapatan Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('admin.laporan.penjualan') }}" data-title="Laporan Penjualan">
                <div class="icon bg-success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h2>Rp {{ number_format($total_pendapatan_hari_ini, 0, ',', '.') }}</h2>
                <p>Pendapatan Hari Ini</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Laporan
                </div>
            </div>
        </div>
        
        <!-- Total Piutang -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('admin.laporan.piutang') }}" data-title="Laporan Piutang">
                <div class="icon bg-warning">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h2>Rp {{ number_format($total_piutang, 0, ',', '.') }}</h2>
                <p>Total Piutang</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Piutang
                </div>
            </div>
        </div>
        
        <!-- Pengeluaran Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('admin.laporan.pengeluaran') }}" data-title="Laporan Pengeluaran">
                <div class="icon bg-danger">
                    <i class="fas fa-cart-arrow-down"></i>
                </div>
                <h2>Rp {{ number_format($total_pengeluaran_hari_ini, 0, ',', '.') }}</h2>
                <p>Pengeluaran Hari Ini</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Pengeluaran
                </div>
            </div>
        </div>
        
        <!-- Transaksi Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="stats-card clickable-card" data-url="{{ route('admin.laporan.penjualan') }}?filter=today" data-title="Transaksi Hari Ini">
                <div class="icon bg-info">
                    <i class="fas fa-receipt"></i>
                </div>
                <h2>{{ $transaksi_baru_hari_ini }}</h2>
                <p>Transaksi Hari Ini</p>
                <div class="card-hover-effect">
                    <i class="fas fa-arrow-right"></i> Lihat Transaksi
                </div>
            </div>
        </div>
    </div>

    <!-- Konten lainnya tetap sama -->
    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Pendapatan 7 Hari Terakhir
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Paket Terpopuler
                </div>
                <div class="card-body">
                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi_terbaru as $transaksi)
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Chart code tetap sama
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels_harian),
            datasets: [{
                label: "Pendapatan",
                lineTension: 0.3,
                backgroundColor: "rgba(44, 123, 229, 0.05)",
                borderColor: "rgba(44, 123, 229, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(44, 123, 229, 1)",
                pointBorderColor: "rgba(44, 123, 229, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(44, 123, 229, 1)",
                pointHoverBorderColor: "rgba(44, 123, 229, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: @json($pendapatan_harian),
            }],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    @if(isset($labels_paket) && isset($data_paket))
    var ctx2 = document.getElementById("myBarChart");
    var myBarChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($labels_paket),
            datasets: [{
                label: "Jumlah Pemakaian",
                backgroundColor: "rgba(40, 167, 69, 0.8)",
                borderColor: "rgba(40, 167, 69, 1)",
                data: @json($data_paket),
            }],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
    @else
    document.getElementById('myBarChart').style.display = 'none';
    document.querySelector('#myBarChart').closest('.card').querySelector('.card-body').innerHTML =
        '<div class="alert alert-info">Belum ada data paket yang digunakan dalam transaksi.</div>';
    @endif

    // Fungsi untuk card yang dapat diklik
    $(document).ready(function() {
        $('.clickable-card').on('click', function() {
            const url = $(this).data('url');
            const title = $(this).data('title');
            
            // Animasi klik
            $(this).addClass('card-clicked');
            setTimeout(() => {
                $(this).removeClass('card-clicked');
                window.location.href = url;
            }, 300);
        });

        // Efek hover
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