@extends('layouts.app')

@section('title', 'Rekap Kendaraan')

@section('content')
<div class="container-fluid pt-4 px-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Rekap Kendaraan</h4>
        <button class="btn btn-sm btn-outline-light" onclick="window.open('{{ route('owner.kendaraan.print') }}', '_blank')">
            <i class="fa fa-print me-2"></i>Print
        </button>
    </div>

    {{-- FILTER TAB --}}
    <div class="mb-3 d-flex gap-2">
        <button class="btn btn-sm btn-outline-light filter-btn active" data-filter="all">
            Semua <span class="badge bg-secondary ms-1">{{ $kendaraans->count() }}</span>
        </button>
        <button class="btn btn-sm btn-outline-success filter-btn" data-filter="masuk">
            Masuk <span class="badge bg-success ms-1">{{ $kendaraans->where('status','masuk')->count() }}</span>
        </button>
        <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="keluar">
            Keluar <span class="badge bg-secondary ms-1">{{ $kendaraans->where('status','keluar')->count() }}</span>
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-secondary rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="kendaraanTable">
                <thead>
                    <tr class="text-white">
                        <th style="width:46px">#</th>
                        <th>Plat Nomor</th>
                        <th>Warna</th>
                        <th>Jenis Kendaraan</th>
                        <th>Area</th>
                        <th>Petugas</th>
                        <th>Waktu Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraans as $index => $k)
                    @php
                    $transaksiAktif = $k->transaksis->where('status','aktif')->first();
                    @endphp
                    <tr data-status="{{ $k->status }}">
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $k->plat_nomor }}</strong></td>
                        <td>{{ $k->warna }}</td>
                        <td>{{ $k->tarif->jenis_kendaraan ?? '-' }}</td>
                        <td>{{ $transaksiAktif->area->nama_area ?? '-' }}</td>
                        <td>{{ $k->user->name ?? '-' }}</td>
                        <td>{{ $k->Created_at ? \Carbon\Carbon::parse($k->Created_at)->format('d M Y, H:i') : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $k->status === 'masuk' ? 'success' : 'secondary' }}">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fa fa-car fa-2x mb-2 d-block text-muted"></i>
                            Belum ada data kendaraan
                        </td>
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
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            document.querySelectorAll('#kendaraanTable tbody tr[data-status]').forEach(row => {
                row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
            });
        });
    });
</script>
@endpush