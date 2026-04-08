@extends('layouts.app')

@section('title', 'Kelola Tarif')

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
        <h4 class="mb-0">Kelola Tarif</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTarifModal">
            <i class="fa fa-plus me-2"></i>Tambah Tarif
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-secondary rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th style="width:46px">#</th>
                        <th>Jenis Kendaraan</th>
                        <th>Tarif Per Jam</th>
                        <th style="width:110px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tarifs as $index => $tarif)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <i class="fa fa-{{ strtolower($tarif->jenis_kendaraan) === 'motor' ? 'motorcycle' : 'car' }} me-2"></i>
                            {{ $tarif->jenis_kendaraan }}
                        </td>
                        <td>Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editTarifModal{{ $tarif->id_tarif }}"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.tarif.destroy', $tarif->id_tarif) }}" method="POST" class="d-inline ms-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus"
                                    onclick="return confirm('Yakin hapus tarif {{ $tarif->jenis_kendaraan }}?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="fa fa-tags fa-2x mb-2 d-block text-muted"></i>
                            Belum ada data tarif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="addTarifModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.tarif.store') }}" method="POST" class="modal-content bg-secondary">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title">Tambah Tarif</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                    <select name="jenis_kendaraan" class="form-select bg-dark border-0 text-white" required>
                        <option value="">— Pilih —</option>
                        <option value="Motor" {{ old('jenis_kendaraan') === 'Motor'  ? 'selected' : '' }}>Motor</option>
                        <option value="Mobil" {{ old('jenis_kendaraan') === 'Mobil'  ? 'selected' : '' }}>Mobil</option>
                        <option value="Truk" {{ old('jenis_kendaraan') === 'Truk'   ? 'selected' : '' }}>Truk</option>
                        <option value="Bus" {{ old('jenis_kendaraan') === 'Bus'    ? 'selected' : '' }}>Bus</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tarif Per Jam <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-0 text-white">Rp</span>
                        <input type="number" name="tarif_per_jam" class="form-control bg-dark border-0 text-white"
                            value="{{ old('tarif_per_jam') }}" placeholder="0" min="0" required>
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

{{-- MODAL EDIT (per tarif) --}}
@foreach($tarifs as $tarif)
<div class="modal fade" id="editTarifModal{{ $tarif->id_tarif }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.tarif.update', $tarif->id_tarif) }}" method="POST" class="modal-content bg-secondary">
            @csrf @method('PUT')
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit Tarif — <span class="text-warning">{{ $tarif->jenis_kendaraan }}</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                    <select name="jenis_kendaraan" class="form-select bg-dark border-0 text-white" required>
                        <option value="Motor" {{ $tarif->jenis_kendaraan === 'Motor' ? 'selected' : '' }}>Motor</option>
                        <option value="Mobil" {{ $tarif->jenis_kendaraan === 'Mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="Truk" {{ $tarif->jenis_kendaraan === 'Truk'  ? 'selected' : '' }}>Truk</option>
                        <option value="Bus" {{ $tarif->jenis_kendaraan === 'Bus'   ? 'selected' : '' }}>Bus</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tarif Per Jam <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-0 text-white">Rp</span>
                        <input type="number" name="tarif_per_jam" class="form-control bg-dark border-0 text-white"
                            value="{{ $tarif->tarif_per_jam }}" min="0" required>
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