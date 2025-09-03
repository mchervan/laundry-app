@extends('layouts.admin')

@section('title', 'Laporan Pengeluaran')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Laporan Pengeluaran</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Laporan Pengeluaran</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filter Laporan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.pengeluaran') }}">
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

    <div class="row">
        <!-- Card Total Pengeluaran -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-light">Total Pengeluaran</h6>
                            <h4 class="font-weight-bold">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Jumlah Transaksi Pengeluaran -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-light">Jumlah Transaksi</h6>
                            <h4 class="font-weight-bold">{{ $pengeluarans->count() }}</h4>
                        </div>
                        <i class="fas fa-receipt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Rata-rata Pengeluaran -->
        <div class="col-xl-4 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-light">Rata-rata per Transaksi</h6>
                            <h4 class="font-weight-bold">
                                Rp {{ $pengeluarans->count() > 0 ? number_format($total / $pengeluarans->count(), 0, ',', '.') : 0 }}
                            </h4>
                        </div>
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart Pengeluaran per Kategori -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Pengeluaran per Kategori
                </div>
                <div class="card-body">
                    @if($kategoriSummary->count() > 0)
                    <canvas id="kategoriChart" width="100%" height="50"></canvas>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Tidak ada data pengeluaran untuk periode yang dipilih.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ringkasan Kategori -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Ringkasan per Kategori
                </div>
                <div class="card-body">
                    @if($kategoriSummary->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Total Pengeluaran</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategoriSummary as $kategori)
                                @php
                                    $persentase = $total > 0 ? ($kategori->total / $total) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $kategori->kategori }}</td>
                                    <td>Rp {{ number_format($kategori->total, 0, ',', '.') }}</td>
                                    <td>{{ number_format($persentase, 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <th>Total</th>
                                    <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                                    <th>100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Tidak ada data pengeluaran untuk periode yang dipilih.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Detail Pengeluaran {{ $cabang != 'all' ? "- Cabang $cabang" : '' }}
            </div>
            <div>
                <a href="{{ route('admin.pengeluaran.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i> Input Pengeluaran
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($pengeluarans->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Cabang</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengeluarans as $pengeluaran)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $pengeluaran->kategori }}</td>
                            <td>{{ $pengeluaran->deskripsi }}</td>
                            <td>{{ $pengeluaran->user->branch ?? '-' }}</td>
                            <td>Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Button Edit -->
                                    <a href="{{ route('admin.pengeluaran.edit', $pengeluaran->id) }}" 
                                       class="btn btn-warning" title="Edit Pengeluaran">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Button Hapus -->
                                    <form action="{{ route('admin.pengeluaran.delete', $pengeluaran->id) }}" method="POST" 
                                          onsubmit="return confirm('Hapus pengeluaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus Pengeluaran">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Tidak ada data pengeluaran untuk periode yang dipilih.
                <a href="{{ route('admin.pengeluaran.create') }}" class="alert-link">Klik di sini untuk input pengeluaran baru</a>.
            </div>
            @endif
        </div>
    </div>
</div>

@if($kategoriSummary->count() > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart untuk Kategori Pengeluaran
    var ctx = document.getElementById('kategoriChart');
    var kategoriChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($kategoriSummary->pluck('kategori')),
            datasets: [{
                data: @json($kategoriSummary->pluck('total')),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#5a5c69', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997'
                ],
                hoverBackgroundColor: [
                    '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617',
                    '#424347', '#59359f', '#d91c6b', '#dc6507', '#199d7a'
                ],
            }],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribusi Pengeluaran per Kategori'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.parsed || 0;
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = Math.round((value / total) * 100);
                            return label + ': Rp ' + value.toLocaleString() + ' (' + percentage + '%)';
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