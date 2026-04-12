@extends('layouts.app')

@section('title', 'Area Parkir')

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
        <h4 class="mb-0">Area Parkir</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.area.print') }}" target="_blank" class="btn btn-success btn-sm">
                <i class="fa fa-print me-2"></i>Cetak
            </a>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAreaModal">
                <i class="fa fa-plus me-2"></i>Tambah Area
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-secondary rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th style="width:46px">#</th>
                        <th>Nama Area</th>
                        <th>Kapasitas</th>
                        <th>Ketersediaan</th>
                        <th style="width:110px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($areas as $index => $area)
                    @php
                    $persen = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas) * 100 : 0;
                    $penuh = $area->terisi >= $area->kapasitas;
                    $sisa = $area->kapasitas - $area->terisi;
                    $warna = $penuh ? 'danger' : ($persen >= 70 ? 'warning' : 'success');
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><i class="fa fa-map-marker-alt me-2"></i>{{ $area->nama_area }}</td>
                        <td>{{ $area->kapasitas }} slot</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $warna }} fs-6 px-3">
                                    {{ $area->terisi }}/{{ $area->kapasitas }}
                                </span>
                                <small class="text-muted">
                                    @if($penuh)
                                    <span class="text-danger">Penuh</span>
                                    @else
                                    Sisa {{ $sisa }} slot
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editAreaModal{{ $area->id_area }}"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.area.destroy', $area->id_area) }}" method="POST" class="d-inline ms-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus"
                                    onclick="return confirm('Yakin hapus area {{ $area->nama_area }}?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fa fa-map-marker-alt fa-2x mb-2 d-block text-muted"></i>
                            Belum ada data area parkir
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="addAreaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.area.store') }}" method="POST" class="modal-content bg-secondary">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title">Tambah Area Parkir</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                    <input type="text" name="nama_area" class="form-control bg-dark border-0 text-white"
                        value="{{ old('nama_area') }}" placeholder="cth: Lantai 1, Zona A" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="kapasitas" class="form-control bg-dark border-0 text-white"
                            value="{{ old('kapasitas') }}" placeholder="0" min="1" required>
                        <span class="input-group-text bg-dark border-0 text-white">slot</span>
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

{{-- MODAL EDIT --}}
@foreach($areas as $area)
<div class="modal fade" id="editAreaModal{{ $area->id_area }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.area.update', $area->id_area) }}" method="POST" class="modal-content bg-secondary">
            @csrf @method('PUT')
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit Area — <span class="text-warning">{{ $area->nama_area }}</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Area <span class="text-danger">*</span></label>
                    <input type="text" name="nama_area" class="form-control bg-dark border-0 text-white"
                        value="{{ $area->nama_area }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="kapasitas" class="form-control bg-dark border-0 text-white"
                            value="{{ $area->kapasitas }}" min="{{ $area->terisi }}" required>
                        <span class="input-group-text bg-dark border-0 text-white">slot</span>
                    </div>
                    @if($area->terisi > 0)
                    <small class="text-warning mt-1 d-block">
                        <i class="fa fa-info-circle me-1"></i>Kapasitas minimal {{ $area->terisi }} (slot terisi saat ini)
                    </small>
                    @endif
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