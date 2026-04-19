@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')

{{-- ===== HEADER ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="mb-3">
        <h5 class="mb-0">Selamat datang, {{ $user->name }} 👋</h5>
        <small class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</small>
    </div>
</div>

{{-- ===== CARDS ===== --}}
<div class="container-fluid px-4">
    <div class="row g-4">

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-coins fa-3x text-warning"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Pendapatan</p>
                    <h6 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-money-bill-wave fa-3x text-success"></i>
                <div class="ms-3">
                    <p class="mb-2">Pendapatan Bulan Ini</p>
                    <h6 class="mb-0">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-exchange-alt fa-3x text-info"></i>
                <div class="ms-3">
                    <p class="mb-2">Total Transaksi</p>
                    <h6 class="mb-0">{{ $totalTransaksi }} transaksi</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-parking fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Sedang Parkir</p>
                    <h6 class="mb-0">{{ $kendaraanParkir }} kendaraan</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-money-bill-wave fa-3x text-info"></i>
                <div class="ms-3">
                    <p class="mb-2">Pendapatan Hari Ini</p>
                    <h6 class="mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-calendar-day fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Transaksi Hari Ini</p>
                    <h6 class="mb-0">{{ $transaksiHariIni }} transaksi</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-clock fa-3x text-danger"></i>
                <div class="ms-3">
                    <p class="mb-2">Belum Bayar (Pending)</p>
                    <h6 class="mb-0">{{ $transaksiPending }} transaksi</h6>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===== AREA PARKIR + KENDARAAN PARKIR ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">

        {{-- Status Area --}}
        <div class="col-12 col-xl-4">
            <div class="bg-secondary rounded p-4 h-100">
                <h6 class="mb-3"><i class="fa fa-map-marker-alt me-2"></i>Status Area Parkir</h6>
                @forelse($areas as $area)
                @php
                $persen = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas) * 100 : 0;
                $penuh = $area->terisi >= $area->kapasitas;
                $warna = $penuh ? 'danger' : ($persen >= 70 ? 'warning' : 'success');
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>{{ $area->nama_area }}</span>
                        <span class="badge bg-{{ $warna }}">
                            {{ $area->terisi }}/{{ $area->kapasitas }}
                            @if($penuh) — Penuh @endif
                        </span>
                    </div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar bg-{{ $warna }}" style="width:{{ $persen }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mt-3">Belum ada area parkir</p>
                @endforelse
            </div>
        </div>

        {{-- Kendaraan Sedang Parkir --}}
        <div class="col-12 col-xl-8">
            <div class="bg-secondary rounded p-4">
                <h6 class="mb-3"><i class="fa fa-car me-2"></i>Kendaraan Sedang Parkir</h6>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-white">
                                <th>#</th>
                                <th>Plat Nomor</th>
                                <th>Jenis</th>
                                <th>Area</th>
                                <th>Masuk</th>
                                <th>Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraanParkirList as $index => $t)
                            @php
                            $durasi = \Carbon\Carbon::parse($t->waktu_masuk)->diff(\Carbon\Carbon::now());
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                                <td>{{ $t->kendaraan->tarif->jenis_kendaraan ?? '-' }}</td>
                                <td>{{ $t->area->nama_area ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y, H:i') }}</td>
                                <td>{{ $durasi->h }}j {{ $durasi->i }}m</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fa fa-car fa-2x mb-2 d-block text-muted"></i>
                                    Tidak ada kendaraan yang sedang parkir
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===== REKAP KENDARAAN ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0"><i class="fa fa-car me-2"></i>Rekap Kendaraan</h6>
            <div class="d-flex gap-2">
                <span class="badge bg-success">Masuk: {{ $rekapKendaraan->where('status','masuk')->count() }}</span>
                <span class="badge bg-secondary">Keluar: {{ $rekapKendaraan->where('status','keluar')->count() }}</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="tabelKendaraan">
                <thead>
                    <tr class="text-white">
                        <th>#</th>
                        <th>Plat Nomor</th>
                        <th>Warna</th>
                        <th>Jenis</th>
                        <th>Petugas</th>
                        <th>Waktu Masuk</th>
                        <th>Total Transaksi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapKendaraan as $i => $k)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $k->plat_nomor }}</strong></td>
                        <td>{{ $k->warna }}</td>
                        <td>{{ $k->tarif->jenis_kendaraan ?? '-' }}</td>
                        <td>{{ $k->user->name ?? '-' }}</td>
                        <td>{{ $k->Created_at ? \Carbon\Carbon::parse($k->Created_at)->format('d M Y, H:i') : '-' }}</td>
                        <td>{{ $k->transaksis->count() }}x</td>
                        <td>
                            <span class="badge bg-{{ $k->status === 'masuk' ? 'success' : 'secondary' }}">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">Belum ada data kendaraan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== REKAP TRANSAKSI ===== --}}
<div class="container-fluid pt-4 px-4 pb-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0"><i class="fa fa-exchange-alt me-2"></i>Rekap Transaksi</h6>
            <div class="d-flex gap-2">
                <span class="badge bg-warning text-dark">Aktif: {{ $rekapTransaksi->where('status','aktif')->count() }}</span>
                <span class="badge bg-danger">Pending: {{ $rekapTransaksi->where('status','pending')->count() }}</span>
                <span class="badge bg-success">Selesai: {{ $rekapTransaksi->where('status','selesai')->count() }}</span>
            </div>
        </div>

        {{-- Filter Tab --}}
        <div class="mb-3 d-flex gap-2">
            <button class="btn btn-sm btn-outline-light filter-transaksi active" data-filter="all">Semua</button>
            <button class="btn btn-sm btn-outline-warning filter-transaksi" data-filter="aktif">Aktif</button>
            <button class="btn btn-sm btn-outline-danger filter-transaksi" data-filter="pending">Pending</button>
            <button class="btn btn-sm btn-outline-success filter-transaksi" data-filter="selesai">Selesai</button>
        </div>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="tabelTransaksi">
                <thead>
                    <tr class="text-white">
                        <th>#</th>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Area</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Durasi</th>
                        <th>Total Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapTransaksi as $i => $t)
                    @php
                    $badgeColor = match($t->status) {
                    'selesai' => 'success',
                    'pending' => 'danger',
                    default => 'warning text-dark',
                    };
                    @endphp
                    <tr data-status="{{ $t->status }}">
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                        <td>{{ $t->kendaraan->tarif->jenis_kendaraan ?? '-' }}</td>
                        <td>{{ $t->area->nama_area ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y, H:i') }}</td>
                        <td>{{ $t->waktu_keluar ? \Carbon\Carbon::parse($t->waktu_keluar)->format('d M Y, H:i') : '-' }}</td>
                        <td>{{ $t->durasi ?? '-' }}</td>
                        <td>
                            @if($t->biaya_total)
                            Rp {{ number_format($t->biaya_total, 0, ',', '.') }}
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($t->metode_pembayaran)
                            <span class="badge bg-{{ $t->metode_pembayaran === 'qris' ? 'info text-dark' : 'light text-dark' }}">
                                <i class="fa fa-{{ $t->metode_pembayaran === 'qris' ? 'qrcode' : 'money-bill-wave' }} me-1"></i>
                                {{ strtoupper($t->metode_pembayaran) }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $badgeColor }}">{{ ucfirst($t->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ===== FILTER TRANSAKSI =====
    document.querySelectorAll('.filter-transaksi').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-transaksi').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('#tabelTransaksi tbody tr[data-status]').forEach(row => {
                row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
            });
        });
    });

    // Auto refresh setiap 60 detik
    setTimeout(() => location.reload(), 60000);
</script>
@endpush