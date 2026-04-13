@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

{{-- ===== CARDS ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="mb-3">
        <h5 class="mb-0">Selamat datang, {{ $user->name }} 👋</h5>
        <small class="text-muted">
            Shift {{ $user->shift ?? '-' }} &nbsp;|&nbsp;
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </small>
    </div>

    <div class="row g-4">

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-sign-in-alt fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Kendaraan Masuk Hari Ini</p>
                    <h6 class="mb-0">{{ $kendaraanMasukHariIni }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-sign-out-alt fa-3x text-warning"></i>
                <div class="ms-3">
                    <p class="mb-2">Kendaraan Keluar Hari Ini</p>
                    <h6 class="mb-0">{{ $kendaraanKeluarHariIni }}</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-parking fa-3x text-success"></i>
                <div class="ms-3">
                    <p class="mb-2">Transaksi Aktif</p>
                    <h6 class="mb-0">{{ $transaksiAktif }}</h6>
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

    </div>
</div>

{{-- ===== GRAFIK ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Grafik Transaksi {{ date('Y') }}</h6>
                </div>
                <canvas id="grafikTransaksi" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ===== AREA PARKIR + TRANSAKSI AKTIF ===== --}}
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">

        {{-- AREA PARKIR --}}
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
                        <div class="progress-bar bg-{{ $warna }}" style="width: {{ $persen }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mt-3">Belum ada area parkir</p>
                @endforelse
            </div>
        </div>

        {{-- TRANSAKSI AKTIF --}}
        <div class="col-12 col-xl-8">
            <div class="bg-secondary rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Kendaraan Sedang Parkir</h6>
                    <a href="{{ route('petugas.transaksi') }}">Lihat Semua</a>
                </div>
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
                            @forelse($transaksiAktifList as $index => $t)
                            @php
                            $durasi = \Carbon\Carbon::parse($t->waktu_masuk)->diff(\Carbon\Carbon::now());
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                                <td>{{ $t->kendaraan->tarif->jenis_kendaraan ?? '-' }}</td>
                                <td>{{ $t->area->nama_area ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('H:i') }}</td>
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

{{-- ===== TRANSAKSI TERBARU ===== --}}
<div class="container-fluid pt-4 px-4 pb-4">
    <div class="bg-secondary rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Transaksi Terbaru Hari Ini</h6>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th>#</th>
                        <th>Waktu Masuk</th>
                        <th>Plat Nomor</th>
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
                        <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                        <td>{{ $t->area->nama_area ?? '-' }}</td>
                        <td>Rp {{ number_format($t->biaya_total ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $t->status === 'selesai' ? 'success' : 'warning' }}">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada transaksi hari ini</td>
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
    const ctx = document.getElementById('grafikTransaksi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {
                !!json_encode($labelBulan) !!
            },
            datasets: [{
                label: 'Jumlah Transaksi',
                data: {
                    !!json_encode($dataGrafik) !!
                },
                backgroundColor: 'rgba(13, 110, 253, 0.7)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#fff'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#fff'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    }
                },
                y: {
                    ticks: {
                        color: '#fff'
                    },
                    grid: {
                        color: 'rgba(255,255,255,0.1)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Auto refresh setiap 60 detik
    setTimeout(() => location.reload(), 60000);
</script>
@endpush