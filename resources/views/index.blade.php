@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('css')
    <!-- jsvectormap css -->
    <link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .dropdown-menu {
            max-height: 200px;
            /* Sesuaikan tinggi dropdown */
            overflow-y: auto;
            /* Aktifkan scroll jika konten melebihi batas */
        }
    </style>
@endsection

@section('page-title')
    Dashboard
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="uim uim-briefcase"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2"> Total User</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $userCount }} <span
                                        class="text-muted font-size-16">User</span>
                                </h3>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="uim uim-layer-group"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2"> Total Data Barang</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $dataBarangCount }} <span
                                        class="text-muted font-size-16">Barang</span>
                                </h3>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="uim uim-scenery"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2">Total Barang Masuk</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $barangMasukCount }} <span
                                        class="text-muted font-size-16">Barang</span>
                                </h3>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md flex-shrink-0">
                                <span class="avatar-title bg-subtle-primary text-primary rounded fs-2">
                                    <i class="uim uim-airplay"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden ms-4">
                                <p class="text-muted text-truncate font-size-15 mb-2"> Total Barang Keluar</p>
                                <h3 class="fs-4 flex-grow-1 mb-3">{{ $barangKeluarCount }} <span
                                        class="text-muted font-size-16">Barang</span></h3>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ROW -->
        <div class="row justify-content-center">
            <!-- Barang Masuk Card -->
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <label for="filterYear" class="form-label me-2">Tahun:</label>
                                <select id="filterYear" class="form-select form-select-sm d-inline w-auto"
                                    onchange="window.location.href = '{{ route('root') }}?year=' + this.value + '&month={{ $selectedMonth }}'">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="filterMonth" class="form-label me-2">Bulan:</label>
                                <select id="filterMonth" class="form-select form-select-sm d-inline w-auto"
                                    onchange="window.location.href = '{{ route('root') }}?year={{ $selectedYear }}&month=' + this.value">
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}"
                                            {{ $selectedMonth == $month ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <tbody>
                                    @forelse($barangMasukMonthly as $barangMasuk)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $barangMasuk->dataBarang->nama_barang }}</h6>
                                                    <p class="text-muted mb-1">Jumlah: {{ $barangMasuk->jumlah }}</p>
                                                    <p class="text-muted mb-0">Tanggal:
                                                        {{ $barangMasuk->tanggal ? $barangMasuk->tanggal->format('d M Y') : '-' }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center text-muted">Tidak ada barang masuk pada periode ini</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barang Keluar Card -->
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <label for="filterYear" class="form-label me-2">Tahun:</label>
                                <select id="filterYear" class="form-select form-select-sm d-inline w-auto"
                                    onchange="window.location.href = '{{ route('root') }}?year=' + this.value + '&month={{ $selectedMonth }}'">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ $selectedYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="filterMonth" class="form-label me-2">Bulan:</label>
                                <select id="filterMonth" class="form-select form-select-sm d-inline w-auto"
                                    onchange="window.location.href = '{{ route('root') }}?year={{ $selectedYear }}&month=' + this.value">
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}"
                                            {{ $selectedMonth == $month ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <tbody>
                                    @forelse($barangKeluarMonthly as $barangKeluar)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $barangKeluar->dataBarang->nama_barang }}</h6>
                                                    <p class="text-muted mb-1">Jumlah: {{ $barangKeluar->jumlah }}</p>
                                                    <p class="text-muted mb-0">Tanggal:
                                                        {{ $barangKeluar->tanggal ? $barangKeluar->tanggal->format('d M Y') : '-' }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center text-muted">Tidak ada barang keluar pada periode ini</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <!-- apexcharts -->
        <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- Vector map-->
        <script src="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.js') }}"></script>
        <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>

        <script src="{{ URL::asset('build/js/pages/dashboard.init.js') }}"></script>
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
