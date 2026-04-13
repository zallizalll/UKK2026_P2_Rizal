@extends('layouts.app')

@section('title', 'Pembayaran Parkir')

@section('content')
<div class="container-fluid pt-4 px-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

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
        <span class="badge bg-danger ms-1">Menunggu Pembayaran</span>
    </div>

    <div class="row g-4">

        {{-- INFO TRANSAKSI --}}
        <div class="col-md-6">
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
        <div class="col-md-6">
            <div class="bg-secondary rounded p-4 h-100">
                <h6 class="text-muted mb-3 text-uppercase" style="font-size:.75rem; letter-spacing:.05em">
                    <i class="fa fa-credit-card me-1"></i> Pilih Metode Pembayaran
                </h6>

                <form action="{{ route('petugas.transaksi.prosesBayar', $transaksi->id_transaksi) }}" method="POST">
                    @csrf

                    {{-- Pilihan metode --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="cash" value="cash" required>
                            <label class="btn btn-outline-light w-100 py-4 d-flex flex-column align-items-center gap-2" for="cash">
                                <i class="fa fa-money-bill-wave fa-2x text-success"></i>
                                <span class="fw-semibold">Cash</span>
                                <small class="text-muted">Bayar Tunai</small>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="qris" value="qris" required>
                            <label class="btn btn-outline-light w-100 py-4 d-flex flex-column align-items-center gap-2" for="qris">
                                <i class="fa fa-qrcode fa-2x text-info"></i>
                                <span class="fw-semibold">QRIS</span>
                                <small class="text-muted">Scan QR Code</small>
                            </label>
                        </div>
                    </div>

                    @error('metode_pembayaran')
                    <div class="text-danger small mb-3">{{ $message }}</div>
                    @enderror

                    {{-- Total ringkas --}}
                    <div class="bg-dark rounded p-3 mb-4 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total yang harus dibayar</span>
                        <span class="fs-5 fw-bold text-success">Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</span>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-check-circle me-2"></i> Konfirmasi Pembayaran
                        </button>
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
    // Highlight kartu metode yang dipilih
    document.querySelectorAll('.btn-check').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.btn-check + label').forEach(l => {
                l.classList.remove('border-success', 'border-info');
            });
            const label = document.querySelector(`label[for="${this.id}"]`);
            label.classList.add(this.value === 'cash' ? 'border-success' : 'border-info');
        });
    });
</script>
@endpush