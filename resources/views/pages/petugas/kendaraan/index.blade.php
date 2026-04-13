@extends('layouts.app')

@section('title', 'Kendaraan')

@section('content')
<div class="container-fluid pt-4 px-4">

    {{-- ALERTS --}}
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

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Kendaraan</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#masukModal">
            <i class="fa fa-sign-in-alt me-2"></i>Kendaraan Masuk
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
                        <th style="width:100px" class="text-center">Aksi</th>
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
                        <td class="text-center">
                            @if($k->status === 'masuk')
                            <form action="{{ route('petugas.kendaraan.keluar', $k->id_kendaraan) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-warning" title="Proses Keluar"
                                    onclick="return confirm('Catat kendaraan {{ $k->plat_nomor }} keluar?')">
                                    <i class="fa fa-sign-out-alt"></i> Keluar
                                </button>
                            </form>
                            @else
                            <span class="text-muted small">Sudah keluar</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
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

{{-- MODAL KENDARAAN MASUK --}}
<div class="modal fade" id="masukModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('petugas.kendaraan.masuk') }}" method="POST" class="modal-content bg-secondary">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title"><i class="fa fa-sign-in-alt me-2 text-success"></i>Kendaraan Masuk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                    <input type="text" name="plat_nomor"
                        class="form-control bg-dark border-0 text-white"
                        value="{{ old('plat_nomor') }}"
                        placeholder="Contoh: B 1234 ABC"
                        style="text-transform:uppercase"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna <span class="text-danger">*</span></label>
                    <input type="text" name="warna"
                        class="form-control bg-dark border-0 text-white"
                        value="{{ old('warna') }}"
                        placeholder="Contoh: Hitam"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                    <select name="id_Tarif" class="form-select bg-dark border-0 text-white" required>
                        <option value="">— Pilih —</option>
                        @foreach($tarifs as $t)
                        <option value="{{ $t->id_tarif }}" {{ old('id_Tarif') == $t->id_tarif ? 'selected' : '' }}>
                            {{ $t->jenis_kendaraan }} — Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}/jam
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Area Parkir <span class="text-danger">*</span></label>
                    <select name="id_area" class="form-select bg-dark border-0 text-white" required>
                        <option value="">— Pilih —</option>
                        @foreach($areas as $a)
                        <option value="{{ $a->id_area }}" {{ old('id_area') == $a->id_area ? 'selected' : '' }}>
                            {{ $a->nama_area }} — Sisa {{ $a->kapasitas - $a->terisi }} slot
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-sign-in-alt me-1"></i> Catat Masuk
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto dismiss alert
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);

    // Filter tab
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