@extends('layouts.admin')

@section('title', 'Input Pengeluaran')

@section('admin-content')
<div class="container-fluid">
    <h1 class="mt-4">Input Pengeluaran</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.laporan.pengeluaran') }}">Laporan Pengeluaran</a></li>
        <li class="breadcrumb-item active">Input Pengeluaran</li>
    </ol>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Form Input Pengeluaran -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-plus-circle me-1"></i>
            Form Input Pengeluaran Baru
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengeluaran.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori Pengeluaran *</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategories as $kategori)
                                    <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>
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
                                    <option value="{{ $branch }}" {{ old('branch') == $branch ? 'selected' : '' }}>
                                        {{ $branch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Pengeluaran *</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                              placeholder="Contoh: Beli detergen 5kg, Bayar listrik bulan Januari" required>{{ old('deskripsi') }}</textarea>
                    <div class="form-text">Jelaskan detail pengeluaran dengan jelas</div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Pengeluaran (Rp) *</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                   min="1000" step="500" placeholder="100000" 
                                   value="{{ old('jumlah') }}" required>
                            <div class="form-text">Minimal Rp 1,000</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Pengeluaran *</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-1"></i> Simpan Pengeluaran
                    </button>
                    <a href="{{ route('admin.laporan.pengeluaran') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Laporan
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Chart Pengeluaran per Kategori -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-pie me-1"></i>
            Distribusi Pengeluaran per Kategori (Bulan Ini)
        </div>
        <div class="card-body">
            @php
                $currentMonth = now()->format('Y-m');
                $monthlySummary = \App\Models\Pengeluaran::where('tanggal', 'like', $currentMonth . '%')
                    ->select('kategori', \DB::raw('SUM(jumlah) as total'))
                    ->groupBy('kategori')
                    ->get();
            @endphp
            
            @if($monthlySummary->count() > 0)
            <canvas id="monthlyChart" width="100%" height="50"></canvas>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Belum ada data pengeluaran untuk bulan ini.
            </div>
            @endif
        </div>
    </div>
</div>

@if($monthlySummary->count() > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart untuk Pengeluaran Bulan Ini
    var ctx = document.getElementById('monthlyChart');
    var monthlyChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($monthlySummary->pluck('kategori')),
            datasets: [{
                data: @json($monthlySummary->pluck('total')),
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
                    text: 'Pengeluaran Bulan {{ now()->format("F Y") }}'
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