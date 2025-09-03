@extends('layouts.kasir')

@section('title', 'Input Order Baru')

@section('kasir-content')
<div class="container-fluid">
    <h1 class="mt-4">Input Order Baru</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Input Order Baru</li>
    </ol>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> 
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Form Order Baru
        </div>
        <div class="card-body">
            <form id="orderForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pelanggan" class="form-label">Cari Pelanggan</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="pelanggan" placeholder="Cari berdasarkan nama atau no HP">
                            <button class="btn btn-outline-primary" type="button" id="btnCariPelanggan">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div id="hasilPencarian" class="mt-2 d-none" style="position: absolute; z-index: 1000; width: 100%;">
                            <div class="list-group" id="listPelanggan"></div>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-success" id="btnTambahPelanggan">
                                <i class="fas fa-plus me-1"></i> Tambah Pelanggan Baru
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="infoPelanggan" class="d-none">
                            <h5>Informasi Pelanggan</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nama</th>
                                    <td id="pelangganNama"></td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td id="pelangganHp"></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td id="pelangganAlamat"></td>
                                </tr>
                            </table>
                            <input type="hidden" id="pelangganId" name="pelanggan_id">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h5>Pilih Paket Laundry</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabelPaket">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Paket</th>
                                        <th>Harga</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyPaket">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th colspan="2" id="totalHarga">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary" id="btnTambahPaket">
                            <i class="fas fa-plus me-1"></i> Tambah Paket
                        </button>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="status_pembayaran" name="status_pembayaran" required>
                            <option value="belum lunas">Belum Lunas</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="catatan" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg" id="btnSimpan">
                        <i class="fas fa-save me-1"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahPelangganModal" tabindex="-1" aria-labelledby="tambahPelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPelangganModalLabel">Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahPelanggan">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama *</label>
                        <input type="text" class="form-control" id="nama" name="nama" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP *</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" autocomplete="off"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pilihPaketModal" tabindex="-1" aria-labelledby="pilihPaketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pilihPaketModalLabel">Pilih Paket Laundry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Paket</th>
                                <th>Jenis</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pakets as $paket)
                            <tr>
                                <td>{{ $paket->nama_paket }}</td>
                                <td>{{ $paket->jenis }}</td>
                                <td>Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($paket->satuan) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary btnPilihPaket" 
                                            data-id="{{ $paket->id }}" 
                                            data-nama="{{ $paket->nama_paket }}" 
                                            data-harga="{{ $paket->harga }}" 
                                            data-satuan="{{ $paket->satuan }}"
                                            data-jenis="{{ $paket->jenis }}">
                                        <i class="fas fa-check"></i> Pilih
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modal {
        backdrop-filter: blur(5px);
    }
    .modal-backdrop {
        background-color: rgba(0,0,0,0.4);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        let selectedItems = [];
        let totalHarga = 0;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#btnCariPelanggan').click(function() {
            searchPelanggan();
        });

        $('#pelanggan').keypress(function(e) {
            if (e.which == 13) {
                e.preventDefault();
                searchPelanggan();
            }
        });

        function searchPelanggan() {
            const keyword = $('#pelanggan').val().trim();
            if (keyword.length < 2) {
                alert('Masukkan minimal 2 karakter untuk pencarian');
                return;
            }

            $.ajax({
                url: "{{ route('kasir.order.cari.pelanggan') }}",
                method: 'POST',
                data: {
                    keyword: keyword
                },
                beforeSend: function() {
                    $('#btnCariPelanggan').html('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    $('#btnCariPelanggan').html('<i class="fas fa-search"></i>');
                    const listPelanggan = $('#listPelanggan');
                    listPelanggan.empty();
                    
                    if (response.length > 0) {
                        response.forEach(pelanggan => {
                            listPelanggan.append(`
                                <a href="#" class="list-group-item list-group-item-action pilih-pelanggan" 
                                   data-id="${pelanggan.id}" 
                                   data-nama="${pelanggan.nama}" 
                                   data-hp="${pelanggan.no_hp}" 
                                   data-alamat="${pelanggan.alamat || '-'}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>${pelanggan.nama}</strong><br>
                                            <small class="text-muted">${pelanggan.no_hp}</small>
                                        </div>
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </a>
                            `);
                        });
                        $('#hasilPencarian').removeClass('d-none');
                    } else {
                        listPelanggan.append(`
                            <div class="list-group-item text-center text-muted">
                                <i class="fas fa-search fa-2x mb-2"></i><br>
                                Tidak ditemukan pelanggan
                            </div>
                        `);
                        $('#hasilPencarian').removeClass('d-none');
                    }
                },
                error: function(xhr) {
                    $('#btnCariPelanggan').html('<i class="fas fa-search"></i>');
                    if (xhr.status === 419) {
                        alert('Session expired. Silakan refresh halaman dan coba lagi.');
                    } else {
                        alert('Terjadi kesalahan saat mencari pelanggan');
                    }
                }
            });
        }

        $(document).on('click', '.pilih-pelanggan', function(e) {
            e.preventDefault();
            
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const hp = $(this).data('hp');
            const alamat = $(this).data('alamat');

            $('#pelangganId').val(id);
            $('#pelangganNama').text(nama);
            $('#pelangganHp').text(hp);
            $('#pelangganAlamat').text(alamat);
            $('#infoPelanggan').removeClass('d-none');
            $('#hasilPencarian').addClass('d-none');
            $('#pelanggan').val('');
        });

        $('#btnTambahPelanggan').click(function() {
            $('#tambahPelangganModal').modal('show');
        });

        $('#formTambahPelanggan').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('kasir.order.simpan.pelanggan') }}",
                method: 'POST',
                data: {
                    nama: $('#nama').val(),
                    no_hp: $('#no_hp').val(),
                    alamat: $('#alamat').val()
                },
                beforeSend: function() {
                    $('#formTambahPelanggan button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                },
                success: function(response) {
                    $('#pelangganId').val(response.id);
                    $('#pelangganNama').text(response.nama);
                    $('#pelangganHp').text(response.no_hp);
                    $('#pelangganAlamat').text(response.alamat || '-');
                    $('#infoPelanggan').removeClass('d-none');
                    $('#tambahPelangganModal').modal('hide');
                    $('#formTambahPelanggan')[0].reset();
                    $('#formTambahPelanggan button[type="submit"]').html('Simpan');
                    
                    alert('Pelanggan berhasil ditambahkan!');
                },
                error: function(xhr) {
                    $('#formTambahPelanggan button[type="submit"]').html('Simpan');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        alert(errorMessage);
                    } else {
                        alert('Terjadi kesalahan saat menyimpan pelanggan');
                    }
                }
            });
        });

        $('#btnTambahPaket').click(function() {
            if (!$('#pelangganId').val()) {
                alert('Pilih pelanggan terlebih dahulu');
                return;
            }
            $('#pilihPaketModal').modal('show');
        });

        $(document).on('click', '.btnPilihPaket', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');
            const satuan = $(this).data('satuan');
            const jenis = $(this).data('jenis');
            
            if ($(`tr[data-paket-id="${id}"]`).length) {
                alert('Paket ini sudah ditambahkan');
                return;
            }
            
            const rowId = `row-${Date.now()}`;
            const newRow = `
                <tr id="${rowId}" data-paket-id="${id}">
                    <td>
                        ${nama}
                        <br><small class="text-muted">${jenis}</small>
                    </td>
                    <td>Rp ${parseFloat(harga).toLocaleString('id-ID')}</td>
                    <td>${satuan}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm jumlah-paket" 
                               min="0.1" step="0.1" value="1" 
                               data-harga="${harga}" style="width: 80px;">
                    </td>
                    <td class="subtotal">Rp ${parseFloat(harga).toLocaleString('id-ID')}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger btnHapusPaket" data-row="${rowId}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#tbodyPaket').append(newRow);
            $('#pilihPaketModal').modal('hide');
            hitungTotal();
        });

        $(document).on('click', '.btnHapusPaket', function() {
            const rowId = $(this).data('row');
            $(`#${rowId}`).remove();
            hitungTotal();
        });

        $(document).on('change', '.jumlah-paket', function() {
            const harga = parseFloat($(this).data('harga'));
            const jumlah = parseFloat($(this).val()) || 0;
            const subtotal = harga * jumlah;
            
            $(this).closest('tr').find('.subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            hitungTotal();
        });

        function hitungTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                const subtotalText = $(this).text().replace('Rp ', '').replace(/\./g, '');
                total += parseFloat(subtotalText) || 0;
            });
            
            $('#totalHarga').text('Rp ' + total.toLocaleString('id-ID'));
        }

        $('#orderForm').submit(function(e) {
            e.preventDefault();
            
            if (!$('#pelangganId').val()) {
                alert('Pilih pelanggan terlebih dahulu');
                return;
            }
            
            if ($('#tbodyPaket tr').length === 0) {
                alert('Tambahkan minimal satu paket laundry');
                return;
            }
            
            let valid = true;
            $('.jumlah-paket').each(function() {
                if (!$(this).val() || parseFloat($(this).val()) <= 0) {
                    alert('Jumlah paket harus lebih dari 0');
                    $(this).focus();
                    valid = false;
                    return false;
                }
            });
            
            if (!valid) return;
            
            const pakets = [];
            $('#tbodyPaket tr').each(function() {
                const paketId = $(this).data('paket-id');
                const jumlah = $(this).find('.jumlah-paket').val();
                const harga = $(this).find('.jumlah-paket').data('harga');
                
                pakets.push({
                    id: paketId,
                    jumlah: parseFloat(jumlah),
                    harga: parseFloat(harga)
                });
            });
            
            const formData = {
                pelanggan_id: $('#pelangganId').val(),
                pakets: pakets,
                status_pembayaran: $('#status_pembayaran').val(),
                catatan: $('#catatan').val()
            };
            
            $.ajax({
                url: "{{ route('kasir.order.simpan.transaksi') }}",
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                beforeSend: function() {
                    $('#btnSimpan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                },
                success: function(response) {
                    if (response.success) {
                        const konfirmasi = confirm(`Transaksi berhasil disimpan dengan kode: ${response.kode_transaksi}\n\nApakah Anda ingin melihat detail transaksi?`);
                        if (konfirmasi) {
                            window.location.href = '/kasir/order/detail/' + response.transaksi_id;
                        } else {
                            window.location.href = "{{ route('kasir.order.list') }}";
                        }
                    }
                },
                error: function(xhr) {
                    $('#btnSimpan').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Transaksi');
                    
                    if (xhr.status === 419) {
                        alert('Session expired. Silakan refresh halaman dan coba lagi.');
                        window.location.reload();
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMessage = 'Validasi gagal:\n';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        alert(errorMessage);
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message);
                    } else {
                        alert('Terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi.');
                    }
                }
            });
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('#hasilPencarian').length && 
                !$(e.target).is('#pelanggan') && 
                !$(e.target).is('#btnCariPelanggan')) {
                $('#hasilPencarian').addClass('d-none');
            }
        });

        $('#pilihPaketModal').on('show.bs.modal', function () {
            $('body').addClass('modal-open');
        });
        
        $('#pilihPaketModal').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
        });
    });
</script>
@endpush
@endsection