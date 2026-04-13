@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="container-fluid pt-4 px-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Transaksi Parkir</h4>
    </div>

    {{-- FILTER TAB --}}
    <div class="mb-3 d-flex gap-2">
        <button class="btn btn-sm btn-outline-light filter-btn active" data-filter="all">
            Semua <span class="badge bg-secondary ms-1">{{ $transaksis->count() }}</span>
        </button>
        <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="aktif">
            Aktif <span class="badge bg-warning text-dark ms-1">{{ $transaksis->where('status','aktif')->count() }}</span>
        </button>
        <button class="btn btn-sm btn-outline-success filter-btn" data-filter="selesai">
            Selesai <span class="badge bg-success ms-1">{{ $transaksis->where('status','selesai')->count() }}</span>
        </button>
    </div>

    <div class="bg-secondary rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0" id="transaksiTable">
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
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $i => $t)
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
                            <span class="badge bg-{{ $t->status === 'selesai' ? 'success' : 'warning text-dark' }}">
                                {{ ucfirst($t->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($t->status === 'selesai')
                            <a href="{{ route('petugas.transaksi.struk', $t->id_transaksi) }}"
                                target="_blank" class="btn btn-sm btn-info" title="Cetak Struk">
                                <i class="fa fa-print"></i>
                            </a>
                            @else
                            <span class="text-muted small">Belum selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="fa fa-exchange-alt fa-2x mb-2 d-block text-muted"></i>
                            Belum ada transaksi
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
            document.querySelectorAll('#transaksiTable tbody tr[data-status]').forEach(row => {
                row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
            });
        });
    });
</script>
@endpush