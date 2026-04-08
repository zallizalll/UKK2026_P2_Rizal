@extends('layouts.app')

@section('title', 'Kelola User')

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
        <h4 class="mb-0">Kelola User</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa fa-user-plus me-2"></i>Tambah User
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-secondary rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th style="width:46px">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width:110px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $user->name }}
                            @if($user->id_user === auth()->user()->id_user)
                            <span class="badge bg-primary ms-1">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{
                                $user->role === 'admin'   ? 'danger' :
                                ($user->role === 'owner'  ? 'warning' : 'info')
                            }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status === 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editUserModal{{ $user->id_user }}"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            @if($user->id_user !== auth()->user()->id_user)
                            <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="d-inline ms-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Hapus"
                                    onclick="return confirm('Yakin hapus user {{ $user->name }}?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fa fa-users fa-2x mb-2 d-block text-muted"></i>
                            Belum ada data user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.users.store') }}" method="POST" class="modal-content bg-secondary">
            @csrf
            <div class="modal-header border-0">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-dark border-0 text-white"
                        value="{{ old('name') }}" placeholder="Nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control bg-dark border-0 text-white"
                        value="{{ old('email') }}" placeholder="email@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control bg-dark border-0 text-white"
                        placeholder="Min. 8 karakter" required>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select bg-dark border-0 text-white" required>
                            <option value="">— Pilih —</option>
                            <option value="admin" {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="owner" {{ old('role') === 'owner'   ? 'selected' : '' }}>Owner</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select bg-dark border-0 text-white" required>
                            <option value="aktif" {{ old('status') !== 'nonaktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-user-plus me-1"></i> Tambah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT (per user) --}}
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id_user }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST" class="modal-content bg-secondary">
            @csrf @method('PUT')
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit — <span class="text-warning">{{ $user->name }}</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control bg-dark border-0 text-white"
                        value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control bg-dark border-0 text-white"
                        value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control bg-dark border-0 text-white"
                        placeholder="Kosongkan jika tidak diubah">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti password.</small>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select bg-dark border-0 text-white" required>
                            <option value="admin" {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ $user->role === 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="owner" {{ $user->role === 'owner'   ? 'selected' : '' }}>Owner</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select bg-dark border-0 text-white" required>
                            <option value="aktif" {{ $user->status === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $user->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
    // Auto-hide alerts setelah 4 detik
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity .5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
@endpush