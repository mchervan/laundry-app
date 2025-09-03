@extends('layouts.kasir')

@section('title', 'Detail Order')

@section('kasir-content')
<div class="container-fluid">
    <h1 class="mt-4">Detail Order</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kasir.order.list') }}">Daftar Order</a></li>
        <li class="breadcrumb-item active">Detail Order</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-receipt me-1"></i>
                    Detail Transaksi: {{ $transaksi->kode_transaksi }}
                </div>
                <div>
                    <button class="btn btn-sm btn-success" onclick="printStruk()">
                        <i class="fas fa-print me-1"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Informasi Transaksi dan Pelanggan -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-info-circle me-1"></i>
                            Informasi Transaksi
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Kode Transaksi</th>
                                    <td>{{ $transaksi->kode_transaksi }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Masuk</th>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>
                                        @if($transaksi->tanggal_selesai)
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y H:i') }}
                                        @else
                                            Belum selesai
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Pembayaran</th>
                                    <td>
                                        @if($transaksi->status_pembayaran == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning">Belum Lunas</span>
                                            <form action="{{ route('kasir.transaksi.lunas', $transaksi->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success ms-2">
                                                    <i class="fas fa-check me-1"></i> Tandai Lunas
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Cucian</th>
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
                                <tr>
                                    <th>Total Harga</th>
                                    <td class="fw-bold">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Cabang</th>
                                    <td>{{ $transaksi->branch }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-user me-1"></i>
                            Informasi Pelanggan
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Nama</th>
                                    <td>{{ $transaksi->pelanggan->nama }}</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td>{{ $transaksi->pelanggan->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $transaksi->pelanggan->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Transaksi</th>
                                    <td>{{ $transaksi->pelanggan->transaksis->count() }}x</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Update Status -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <i class="fas fa-sync-alt me-1"></i>
                    Update Status Order
                </div>
                <div class="card-body">
                    <form action="{{ route('kasir.order.update.status', $transaksi->id) }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="status_cucian" class="form-label">Status Cucian</label>
                                <select class="form-select" id="status_cucian" name="status_cucian" required>
                                    <option value="antrian" {{ $transaksi->status_cucian == 'antrian' ? 'selected' : '' }}>Antrian</option>
                                    <option value="proses cuci" {{ $transaksi->status_cucian == 'proses cuci' ? 'selected' : '' }}>Proses Cuci</option>
                                    <option value="proses kering" {{ $transaksi->status_cucian == 'proses kering' ? 'selected' : '' }}>Proses Kering</option>
                                    <option value="proses setrika" {{ $transaksi->status_cucian == 'proses setrika' ? 'selected' : '' }}>Proses Setrika</option>
                                    <option value="siap diambil" {{ $transaksi->status_cucian == 'siap diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                    <option value="selesai" {{ $transaksi->status_cucian == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Tambahkan catatan jika perlu">{{ $transaksi->catatan }}</textarea>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save me-1"></i> Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Detail Items -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-list me-1"></i>
                    Detail Items Laundry
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>Paket Laundry</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->details as $detail)
                                <tr>
                                    <td>{{ $detail->paket->nama_paket }}</td>
                                    <td>{{ $detail->jumlah }} {{ $detail->paket->satuan }}</td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-success">
                                <tr>
                                    <th colspan="3" class="text-end">Total Harga</th>
                                    <th>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            @if($transaksi->catatan)
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-sticky-note me-1"></i>
                    Catatan Khusus
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $transaksi->catatan }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Struk Cetakan Professional dengan Info Cabang -->
    <div class="d-none">
        <div id="struk-print" class="struk-container">
            <div class="struk-header">
                <div class="text-center">
                    <h2 class="company-name">LAUNDRY EXPRESS</h2>
                    <p class="company-address">Jl. Contoh No. 123, Kota Contoh</p>
                    <p class="company-contact">Telp: 0812-3456-7890 | WhatsApp: 0812-3456-7890</p>
                    
                    <!-- Informasi Cabang -->
                    <div class="branch-info">
                        <h3 class="branch-name">{{ $transaksi->branch ?? auth()->user()->branch }}</h3>
                        <p class="branch-address">
                            @if(($transaksi->branch ?? auth()->user()->branch) == 'Pusat')
                                Jl. Pusat No. 001, Kota Contoh
                            @elseif(($transaksi->branch ?? auth()->user()->branch) == 'Cabang A')
                                Jl. Cabang A No. 123, Kota Contoh
                            @elseif(($transaksi->branch ?? auth()->user()->branch) == 'Cabang B')
                                Jl. Cabang B No. 456, Kota Contoh
                            @elseif(($transaksi->branch ?? auth()->user()->branch) == 'Cabang C')
                                Jl. Cabang C No. 789, Kota Contoh
                            @else
                                Jl. Utama No. 001, Kota Contoh
                            @endif
                        </p>
                    </div>
                    
                    <div class="divider"></div>
                </div>
            </div>

            <div class="struk-body">
                <table class="struk-info">
                    <tr>
                        <td width="40%">Kode Transaksi</td>
                        <td width="60%">: {{ $transaksi->kode_transaksi }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Pelanggan</td>
                        <td>: {{ $transaksi->pelanggan->nama }}</td>
                    </tr>
                    <tr>
                        <td>No. HP</td>
                        <td>: {{ $transaksi->pelanggan->no_hp }}</td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td>: {{ strtoupper($transaksi->status_pembayaran) }}</td>
                    </tr>
                    <tr>
                        <td>Cabang</td>
                        <td>: {{ $transaksi->branch ?? auth()->user()->branch }}</td>
                    </tr>
                </table>

                <div class="divider"></div>

                <table class="struk-items">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->details as $detail)
                        <tr>
                            <td>{{ $detail->paket->nama_paket }}</td>
                            <td class="text-right">{{ $detail->jumlah }} {{ $detail->paket->satuan }}</td>
                            <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="divider"></div>

                <table class="struk-total">
                    <tr>
                        <td width="70%">SUB TOTAL</td>
                        <td width="30%" class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-right"><strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>

                @if($transaksi->catatan)
                <div class="divider"></div>
                <div class="struk-notes">
                    <p><strong>Catatan:</strong> {{ $transaksi->catatan }}</p>
                </div>
                @endif

                <div class="divider"></div>

                <div class="struk-footer">
                    <p class="text-center thank-you">Terima kasih atas kepercayaan Anda</p>
                    <p class="text-center warning">** Barang yang sudah dibayar tidak dapat dikembalikan **</p>
                    <p class="text-center info">Simpan struk ini sebagai bukti transaksi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Style untuk struk cetakan */
.struk-container {
    width: 80mm;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    padding: 10px;
    margin: 0 auto;
    background: white;
}

.company-name {
    font-size: 16px;
    font-weight: bold;
    margin: 5px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.company-address, .company-contact {
    font-size: 10px;
    margin: 2px 0;
    line-height: 1.2;
}

.branch-info {
    margin: 10px 0;
    padding: 8px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 5px;
    border: 1px dashed #6c757d;
}

.branch-name {
    font-size: 14px;
    font-weight: bold;
    margin: 0;
    color: #2c3e50;
    text-transform: uppercase;
}

.branch-address {
    font-size: 10px;
    margin: 2px 0 0 0;
    color: #6c757d;
}

.divider {
    border-top: 1px dashed #000;
    margin: 8px 0;
}

.struk-info {
    width: 100%;
    margin-bottom: 8px;
}

.struk-info td {
    padding: 2px 0;
    font-size: 11px;
    vertical-align: top;
}

.struk-items {
    width: 100%;
    margin: 8px 0;
    border-collapse: collapse;
}

.struk-items th {
    border-bottom: 1px solid #000;
    padding: 4px 2px;
    text-align: left;
    font-weight: bold;
}

.struk-items th.text-right {
    text-align: right;
}

.struk-items td {
    padding: 3px 2px;
    border-bottom: 1px dotted #ccc;
    vertical-align: top;
}

.struk-total {
    width: 100%;
    margin: 8px 0;
    font-weight: bold;
}

.struk-total td {
    padding: 4px 2px;
}

.struk-total tr:last-child td {
    border-top: 1px solid #000;
    padding-top: 6px;
}

.struk-notes {
    margin: 8px 0;
    font-size: 11px;
    padding: 5px;
    background: #f8f9fa;
    border-left: 3px solid #007bff;
}

.struk-footer {
    margin-top: 15px;
    padding-top: 10px;
}

.thank-you {
    font-weight: bold;
    margin: 5px 0;
    font-size: 11px;
}

.warning {
    font-size: 9px;
    color: #d32f2f;
    margin: 3px 0;
    font-weight: bold;
}

.info {
    font-size: 9px;
    font-style: italic;
    margin: 3px 0;
    color: #6c757d;
}

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

/* Media query untuk print */
@media print {
    body * {
        visibility: hidden;
        margin: 0;
        padding: 0;
    }
    
    #struk-print, #struk-print * {
        visibility: visible;
    }
    
    #struk-print {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm !important;
        max-width: 80mm !important;
        margin: 0 !important;
        padding: 10px !important;
        font-size: 12px !important;
        line-height: 1.2 !important;
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        border: none !important;
    }
    
    .no-print {
        display: none !important;
    }
    
    /* Hindari page break dalam struk */
    .struk-container {
        page-break-inside: avoid;
        break-inside: avoid;
    }
}
</style>

<script>
function printStruk() {
    // Simpan konten asli
    var originalContent = document.body.innerHTML;
    var printContent = document.getElementById('struk-print').innerHTML;
    
    // Buat window baru untuk print
    var printWindow = window.open('', '_blank', 'width=800,height=600');
    
    // Tulis konten struk ke window baru
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Cetak Struk - {{ $transaksi->kode_transaksi }}</title>
            <style>
                body { 
                    margin: 0; 
                    padding: 0; 
                    font-family: 'Courier New', monospace;
                    background: white;
                }
                .struk-container {
                    width: 80mm;
                    margin: 0 auto;
                    padding: 10px;
                    background: white;
                }
                .company-name {
                    font-size: 16px;
                    font-weight: bold;
                    margin: 5px 0;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    text-align: center;
                }
                .company-address, .company-contact {
                    font-size: 10px;
                    margin: 2px 0;
                    line-height: 1.2;
                    text-align: center;
                }
                .branch-info {
                    margin: 10px 0;
                    padding: 8px;
                    background: #f8f9fa;
                    border-radius: 5px;
                    border: 1px dashed #6c757d;
                    text-align: center;
                }
                .branch-name {
                    font-size: 14px;
                    font-weight: bold;
                    margin: 0;
                    color: #2c3e50;
                    text-transform: uppercase;
                }
                .branch-address {
                    font-size: 10px;
                    margin: 2px 0 0 0;
                    color: #6c757d;
                }
                .divider {
                    border-top: 1px dashed #000;
                    margin: 8px 0;
                }
                .struk-info {
                    width: 100%;
                    margin-bottom: 8px;
                    font-size: 11px;
                }
                .struk-info td {
                    padding: 2px 0;
                    vertical-align: top;
                }
                .struk-items {
                    width: 100%;
                    margin: 8px 0;
                    border-collapse: collapse;
                    font-size: 11px;
                }
                .struk-items th {
                    border-bottom: 1px solid #000;
                    padding: 4px 2px;
                    text-align: left;
                    font-weight: bold;
                }
                .struk-items th.text-right {
                    text-align: right;
                }
                .struk-items td {
                    padding: 3px 2px;
                    border-bottom: 1px dotted #ccc;
                    vertical-align: top;
                }
                .struk-total {
                    width: 100%;
                    margin: 8px 0;
                    font-weight: bold;
                    font-size: 11px;
                }
                .struk-total td {
                    padding: 4px 2px;
                }
                .struk-total tr:last-child td {
                    border-top: 1px solid #000;
                    padding-top: 6px;
                }
                .struk-notes {
                    margin: 8px 0;
                    font-size: 11px;
                    padding: 5px;
                    background: #f8f9fa;
                    border-left: 3px solid #007bff;
                }
                .struk-footer {
                    margin-top: 15px;
                    padding-top: 10px;
                    text-align: center;
                }
                .thank-you {
                    font-weight: bold;
                    margin: 5px 0;
                    font-size: 11px;
                }
                .warning {
                    font-size: 9px;
                    color: #d32f2f;
                    margin: 3px 0;
                    font-weight: bold;
                }
                .info {
                    font-size: 9px;
                    font-style: italic;
                    margin: 3px 0;
                    color: #6c757d;
                }
                .text-right {
                    text-align: right;
                }
                .text-center {
                    text-align: center;
                }
                
                @media print {
                    body {
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                    .struk-container {
                        width: 80mm !important;
                        max-width: 80mm !important;
                        margin: 0 auto !important;
                        padding: 10px !important;
                    }
                }
            </style>
        </head>
        <body>
            ${printContent}
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() {
                        window.close();
                    }, 500);
                }
            <\/script>
        </body>
        </html>
    `);
    
    printWindow.document.close();
}

// Auto print jika parameter print ada
@if(request()->has('print'))
window.onload = function() {
    setTimeout(function() {
        printStruk();
    }, 1000);
}
@endif
</script>
@endsection