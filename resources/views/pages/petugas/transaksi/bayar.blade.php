@extends('layouts.app')

@section('title', 'Pembayaran Parkir')

@section('content')
<div class="container-fluid pt-4 px-4">

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('petugas.transaksi') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h4 class="mb-0">Pembayaran Parkir</h4>
        <span class="badge bg-danger">Menunggu Pembayaran</span>
    </div>

    <div class="row g-4">

        {{-- INFO TRANSAKSI --}}
        <div class="col-md-5">
            <div class="bg-secondary rounded p-4 h-100">
                <h6 class="text-muted mb-3 text-uppercase" style="font-size:.75rem; letter-spacing:.05em">
                    <i class="fa fa-car me-1"></i> Info Kendaraan & Parkir
                </h6>
                <table class="table table-borderless text-white mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted ps-0" style="width:140px">Plat Nomor</td>
                            <td><strong>{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Jenis</td>
                            <td>{{ $transaksi->kendaraan->tarif->jenis_kendaraan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Area Parkir</td>
                            <td>{{ $transaksi->area->nama_area ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Waktu Masuk</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Waktu Keluar</td>
                            <td>{{ $transaksi->waktu_keluar ? \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Durasi</td>
                            <td>{{ $transaksi->durasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Tarif/Jam</td>
                            <td>Rp {{ number_format($transaksi->kendaraan->tarif->tarif_per_jam ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-top">
                            <td class="text-muted ps-0 pt-3">Total Bayar</td>
                            <td class="pt-3">
                                <span class="fs-5 fw-bold text-success">
                                    Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PILIH METODE BAYAR --}}
        <div class="col-md-7">
            <div class="bg-secondary rounded p-4">
                <h6 class="text-muted mb-3 text-uppercase" style="font-size:.75rem; letter-spacing:.05em">
                    <i class="fa fa-credit-card me-1"></i> Pilih Metode Pembayaran
                </h6>

                <form action="{{ route('petugas.transaksi.prosesBayar', $transaksi->id_transaksi) }}" method="POST" id="formBayar">
                    @csrf

                    {{-- Pilihan metode --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="cash" value="cash" required>
                            <label class="btn btn-outline-light w-100 py-3 d-flex flex-column align-items-center gap-2" for="cash">
                                <i class="fa fa-money-bill-wave fa-2x text-success"></i>
                                <span class="fw-semibold">Cash</span>
                                <small class="text-muted">Bayar Tunai</small>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="qris" value="qris" required>
                            <label class="btn btn-outline-light w-100 py-3 d-flex flex-column align-items-center gap-2" for="qris">
                                <i class="fa fa-qrcode fa-2x text-info"></i>
                                <span class="fw-semibold">QRIS</span>
                                <small class="text-muted">Scan QR Code</small>
                            </label>
                        </div>
                    </div>

                    @error('metode_pembayaran')
                    <div class="text-danger small mb-3">{{ $message }}</div>
                    @enderror

                    {{-- SECTION CASH --}}
                    <div id="section-cash" class="d-none mb-4">
                        <div class="bg-dark rounded p-4 text-center">
                            <i class="fa fa-money-bill-wave fa-3x text-success mb-3"></i>
                            <h5 class="text-white mb-1">Pembayaran Tunai</h5>
                            <p class="text-muted small mb-3">Terima uang dari pengemudi lalu konfirmasi di bawah</p>
                            <div class="bg-secondary rounded p-3">
                                <div class="text-muted small mb-1">Total yang harus dibayar</div>
                                <div class="fs-3 fw-bold text-success">
                                    Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION QRIS --}}
                    <div id="section-qris" class="d-none mb-4">
                        <div class="bg-dark rounded p-4 text-center">
                            <p class="text-muted small mb-3">Tunjukkan QR Code ini ke pengemudi untuk di-scan</p>

                            {{-- QR Code --}}
                            <div class="d-flex justify-content-center mb-3">
                                <div class="bg-white p-2 rounded" style="display:inline-block">
                                    {{-- 
                                        Untuk ganti gambar QRIS lo sendiri:
                                        1. Taruh gambar di public/img/qris.png (atau .svg)
                                        2. Ganti src di bawah jadi: {{ asset('img/qris.png') }}
                                    --}}
                                    <img src="{{ asset('img/qris.svg') }}"
                                         alt="QRIS"
                                         style="width:220px; height:220px; object-fit:contain;"
                                         id="img-qris">
                                </div>
                            </div>

                            <div class="bg-secondary rounded p-3">
                                <div class="text-muted small mb-1">Total yang harus dibayar</div>
                                <div class="fs-3 fw-bold text-success">
                                    Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}
                                </div>
                            </div>
                            <p class="text-muted small mt-2 mb-0">
                                <i class="fa fa-info-circle me-1"></i>
                                Scan dengan aplikasi bank atau e-wallet manapun
                            </p>
                        </div>
                    </div>

                    {{-- Tombol konfirmasi (muncul setelah pilih metode) --}}
                    <div id="btn-konfirmasi" class="d-none d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-check-circle me-2"></i>
                            <span id="btn-label">Konfirmasi Pembayaran</span>
                        </button>
                        <a href="{{ route('petugas.transaksi') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-clock me-1"></i> Tunda — Bayar Nanti
                        </a>
                    </div>

                    {{-- Tombol tunda (muncul sebelum pilih metode) --}}
                    <div id="btn-tunda" class="d-grid">
                        <a href="{{ route('petugas.transaksi') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-clock me-1"></i> Tunda — Bayar Nanti
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const radioCash      = document.getElementById('cash');
    const radioQris      = document.getElementById('qris');
    const sectionCash    = document.getElementById('section-cash');
    const sectionQris    = document.getElementById('section-qris');
    const btnKonfirmasi  = document.getElementById('btn-konfirmasi');
    const btnTunda       = document.getElementById('btn-tunda');
    const btnLabel       = document.getElementById('btn-label');

    function updateTampilan(metode) {
        sectionCash.classList.add('d-none');
        sectionQris.classList.add('d-none');
        btnTunda.classList.add('d-none');
        btnKonfirmasi.classList.remove('d-none');

        if (metode === 'cash') {
            sectionCash.classList.remove('d-none');
            btnLabel.textContent = 'Konfirmasi Pembayaran Cash';
        } else {
            sectionQris.classList.remove('d-none');
            btnLabel.textContent = 'Konfirmasi Pembayaran QRIS';
        }

        // Highlight border label yang dipilih
        document.querySelectorAll('.btn-check + label').forEach(l => {
            l.classList.remove('border-success', 'border-info', 'border-2');
        });
        const lbl = document.querySelector(`label[for="${metode}"]`);
        lbl.classList.add(metode === 'cash' ? 'border-success' : 'border-info', 'border-2');
    }

    radioCash.addEventListener('change', () => updateTampilan('cash'));
    radioQris.addEventListener('change', () => updateTampilan('qris'));
</script>
@endpush