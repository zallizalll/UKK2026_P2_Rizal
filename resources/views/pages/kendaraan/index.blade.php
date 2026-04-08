@extends('layouts.app')

@section('title', 'Kelola Kendaraan')

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
        <h4 class="mb-0">Kelola Kendaraan</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addKendaraanModal">
            <i class="fa fa-plus me-2"></i>Tambah Kendaraan
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-secondary rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th style="width:46px">#</th>
                        <th>Plat Nomor</th>
                        <th>Warna</th>
                        <th>Status</th>
                        <th>Tarif</th>
                        <th>User</th>
                        <th>Dibuat</th>
                        <th style="width:110px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kendaraans as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $k->plat_nomor }}</strong></td>
                        <td>{{ $k->warna }}</td>
                        <td>
                            <span class="badge bg-{{ $k->status === 'masuk' ? 'success' : 'secondary' }}">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                        {{-- FIX: ganti nama_tarif -> jenis_kendaraan --}}
                        <td>{{ $k->tarif->jenis_kendaraan ?? '-' }}</td>
                        <td>{{ $k->user->name ?? '-' }}</td>
                        <td>{{ $k->Created_at ? \Carbon\Carbon::parse($k->Created_at)->format('d M Y') : '-' }}</td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editKendaraanModal{{ $k->id_kendaraan }}"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.kendaraan.destroy', $k->id_kendaraan) }}" method="POST" class="d-inline ms-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus"
                                    onclick="return confirm('Yakin hapus kendaraan {{ $k->plat_nomor }}?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
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

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="addKendaraanModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.kendaraan.store') }}" method="POST" class="modal-content bg-secondary">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title">Tambah Kendaraan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                    <input type="text" name="plat_nomor" class="form-control bg-dark border-0 text-white"
                        value="{{ old('plat_nomor') }}" placeholder="Contoh: B 1234 ABC" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna <span class="text-danger">*</span></label>
                    <input type="text" name="warna" class="form-control bg-dark border-0 text-white"
                        value="{{ old('warna') }}" placeholder="Contoh: Hitam" required>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select bg-dark border-0 text-white" required>
                            <option value="masuk" {{ old('status') === 'masuk'  ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ old('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tarif <span class="text-danger">*</span></label>
                        <select name="id_Tarif" class="form-select bg-dark border-0 text-white" required>
                            <option value="">— Pilih —</option>
                            @foreach($tarifs as $t)
                            {{-- FIX: ganti id_Tarif -> id_tarif dan nama_tarif -> jenis_kendaraan --}}
                            <option value="{{ $t->id_tarif }}" {{ old('id_Tarif') == $t->id_tarif ? 'selected' : '' }}>
                                {{ $t->jenis_kendaraan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">User/Petugas <span class="text-danger">*</span></label>
                        <select name="id_user" class="form-select bg-dark border-0 text-white" required>
                            <option value="">— Pilih —</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id_user }}" {{ old('id_user') == $u->id_user ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT (per kendaraan) --}}
@foreach($kendaraans as $k)
<div class="modal fade" id="editKendaraanModal{{ $k->id_kendaraan }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.kendaraan.update', $k->id_kendaraan) }}" method="POST" class="modal-content bg-secondary">
            @csrf @method('PUT')
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit — <span class="text-warning">{{ $k->plat_nomor }}</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                    <input type="text" name="plat_nomor" class="form-control bg-dark border-0 text-white"
                        value="{{ $k->plat_nomor }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Warna <span class="text-danger">*</span></label>
                    <input type="text" name="warna" class="form-control bg-dark border-0 text-white"
                        value="{{ $k->warna }}" required>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select bg-dark border-0 text-white" required>
                            <option value="masuk" {{ $k->status === 'masuk'  ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ $k->status === 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Tarif <span class="text-danger">*</span></label>
                        <select name="id_Tarif" class="form-select bg-dark border-0 text-white" required>
                            @foreach($tarifs as $t)
                            {{-- FIX: ganti id_Tarif -> id_tarif dan nama_tarif -> jenis_kendaraan --}}
                            <option value="{{ $t->id_tarif }}" {{ $k->id_Tarif == $t->id_tarif ? 'selected' : '' }}>
                                {{ $t->jenis_kendaraan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">User/Petugas <span class="text-danger">*</span></label>
                        <select name="id_user" class="form-select bg-dark border-0 text-white" required>
                            @foreach($users as $u)
                            <option value="{{ $u->id_user }}" {{ $k->id_user == $u->id_user ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
@endpush