@extends('layouts.admin')

@section('title', 'Laporan Performa Paket')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Laporan Performa Paket</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Performa</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Laporan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.performa') }}">
                <div class="row">
                    <div class="col-md-5">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $start }}" max="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-5">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $end }}" max="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($pakets->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-pie me-1"></i>
            Grafik Performa Paket
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <canvas id="pieChart" width="100%" height="50"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="barChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Performa Paket
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Paket</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Pendapatan</th>
                            <th>Rata-rata per Transaksi</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSemuaTransaksi = $pakets->sum('jumlah_transaksi');
                            $totalSemuaPendapatan = $pakets->sum('total_pendapatan');
                        @endphp
                        
                        @foreach($pakets as $paket)
                        @php
                            $rataRata = $paket->jumlah_transaksi > 0 
                                ? $paket->total_pendapatan / $paket->jumlah_transaksi 
                                : 0;
                                
                            $persentase = $totalSemuaPendapatan > 0 
                                ? ($paket->total_pendapatan / $totalSemuaPendapatan) * 100 
                                : 0;
                        @endphp
                        <tr>
                            <td>{{ $paket->nama_paket }}</td>
                            <td>{{ number_format($paket->jumlah_transaksi, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($paket->total_pendapatan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($rataRata, 0, ',', '.') }}</td>
                            <td>{{ number_format($persentase, 2) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th>Total</th>
                            <th>{{ number_format($totalSemuaTransaksi, 0, ',', '.') }}</th>
                            <th>Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}</th>
                            <th>-</th>
                            <th>100%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Performa Paket
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> 
                Tidak ada data transaksi untuk periode yang dipilih. 
                @if($start != now()->format('Y-m-d'))
                <br>Coba <a href="{{ route('admin.laporan.performa', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}">lihat data hari ini</a>.
                @endif
            </div>
            
            <div class="mt-3">
                <h6>Kemungkinan penyebab:</h6>
                <ul class="small">
                    <li>Belum ada transaksi pada periode {{ $start }} hingga {{ $end }}</li>
                    <li>Transaksi yang ada belum berstatus "Lunas"</li>
                    <li>Data paket laundry belum dibuat</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

@if($pakets->count() > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart
    var ctx = document.getElementById('pieChart');
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($pakets->pluck('nama_paket')),
            datasets: [{
                data: @json($pakets->pluck('jumlah_transaksi')),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#5a5c69', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997'
                ],
                hoverBackgroundColor: [
                    '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617',
                    '#424347', '#59359f', '#d91c6b', '#dc6507', '#199d7a'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribusi Jumlah Transaksi'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.parsed || 0;
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = Math.round((value / total) * 100);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Bar Chart
    var ctx2 = document.getElementById('barChart');
    var barChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($pakets->pluck('nama_paket')),
            datasets: [{
                label: 'Total Pendapatan',
                backgroundColor: '#1cc88a',
                hoverBackgroundColor: '#17a673',
                borderColor: '#1cc88a',
                data: @json($pakets->pluck('total_pendapatan')),
            }]
        },
        options: {
            responsive: true,
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
                title: {
                    display: true,
                    text: 'Total Pendapatan per Paket'
                },
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
</script>
@endpush
@endif
@endsection