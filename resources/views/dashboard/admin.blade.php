@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- ===== CARDS ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-car fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Kendaraan</p>
                    <h6 class="mb-0">{{ $totalKendaraan }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-tags fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Tarif</p>
                    <h6 class="mb-0">{{ $totalTarif }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-map-marker-alt fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Area Parkir</p>
                    <h6 class="mb-0">{{ $totalArea }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-users fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total User</p>
                    <h6 class="mb-0">{{ $totalUser }}</h6>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===== TABEL TRANSAKSI TERBARU ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Transaksi Terbaru</h6>
            <a href="#">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>No. Plat</th>
                        <th>Area</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $i => $t)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y H:i') }}</td>
                        <td>{{ $t->id_kendaraan }}</td>
                        <td>{{ $t->id_area }}</td>
                        <td>Rp {{ number_format($t->biaya_total ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $t->status === 'selesai' ? 'success' : 'warning' }}">
                                {{ ucfirst($t->status ?? '-') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== TABEL KENDARAAN PARKIR ===== --}}
<div class="container-fluid pt-4 px-4 pb-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Data Kendaraan Parkir</h6>
            <a href="#">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th>#</th>
                        <th>No. Plat</th>
                        <th>Jenis</th>
                        <th>Waktu Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraanParkir as $i => $k)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $k->no_plat ?? '-' }}</td>
                        <td>{{ $k->jenis_kendaraan ?? '-' }}</td>
                        <td>{{ isset($k->created_at) ? \Carbon\Carbon::parse($k->created_at)->format('d M Y H:i') : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $k->status === 'parkir' ? 'success' : 'secondary' }}">
                                {{ ucfirst($k->status ?? '-') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data kendaraan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
