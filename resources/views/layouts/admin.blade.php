@extends('layouts.app')

@section('content')
<div class="d-flex" id="wrapper">
    <div class="bg-dark" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4">
            <i class="fas fa-tshirt me-2"></i>Laundry Express
        </div>
        <div class="list-group list-group-flush">
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.laporan.penjualan') }}" class="list-group-item list-group-item-action {{ Request::is('admin/laporan/penjualan*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart me-2"></i>Laporan Penjualan
            </a>
            <a href="{{ route('admin.laporan.piutang') }}" class="list-group-item list-group-item-action {{ Request::is('admin/laporan/piutang*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar me-2"></i>Laporan Piutang
            </a>
            <a href="{{ route('admin.laporan.performa') }}" class="list-group-item list-group-item-action {{ Request::is('admin/laporan/performa*') ? 'active' : '' }}">
                <i class="fas fa-star me-2"></i>Laporan Performa
            </a>
            <a href="{{ route('admin.laporan.pengeluaran') }}" class="list-group-item list-group-item-action {{ Request::is('admin/laporan/pengeluaran*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave me-2"></i>Laporan Pengeluaran
            </a>
            <a href="{{ route('admin.manajemen.paket') }}" class="list-group-item list-group-item-action {{ Request::is('admin/manajemen/paket*') ? 'active' : '' }}">
                <i class="fas fa-box-open me-2"></i>Kelola Paket
            </a>
            <a href="{{ route('admin.manajemen.karyawan') }}" class="list-group-item list-group-item-action {{ Request::is('admin/manajemen/karyawan*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i>Kelola Cabang
            </a>
            <a href="{{ route('admin.manajemen.pelanggan') }}" class="list-group-item list-group-item-action {{ Request::is('admin/manajemen/pelanggan*') ? 'active' : '' }}">
                <i class="fas fa-address-book me-2"></i>Kelola Pelanggan
            </a>
        </div>
    </div>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary btn-sm" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4">
            @yield('admin-content')
        </div>
    </div>
</div>
@endsection