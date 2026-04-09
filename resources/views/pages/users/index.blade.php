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

        <div class="d-flex gap-2">
            {{-- PRINT BUTTON --}}
            <a href="{{ route('admin.users.print', 'admin') }}" target="_blank"
                class="btn btn-danger btn-sm">
                <i class="fa fa-print me-1"></i> Admin
            </a>

            <a href="{{ route('admin.users.print', 'petugas') }}" target="_blank"
                class="btn btn-info btn-sm">
                <i class="fa fa-print me-1"></i> Petugas
            </a>

            <a href="{{ route('admin.users.print', 'owner') }}" target="_blank"
                class="btn btn-warning btn-sm">
                <i class="fa fa-print me-1"></i> Owner
            </a>

            {{-- TAMBAH USER --}}
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fa fa-user-plus me-2"></i>Tambah User
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Shift</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width:110px" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    @php
                    $hour = now()->hour;
                    $shiftActive = match($user->shift) {
                    '1' => $hour >= 5 && $hour < 12, '2'=> $hour >= 12 && $hour < 19, '3'=> $hour >= 19 || $hour < 2,
                                default=> false,
                                };
                                // Status efektif: override manual kalau status_override = true, selain itu ikut shift
                                $effectiveStatus = $user->status_override
                                ? $user->status
                                : ($user->shift ? ($shiftActive ? 'aktif' : 'nonaktif') : $user->status);
                                @endphp
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
                                        $user->role === 'admin' ? 'danger' :
                                        ($user->role === 'owner' ? 'warning' : 'info')
                                        }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>

                                    {{-- SHIFT --}}
                                    <td>
                                        @if($user->shift)
                                        @php
                                        $shiftLabel = ['1' => 'Pagi', '2' => 'Siang', '3' => 'Malam'];
                                        $shiftIcon = ['1' => '🌅', '2' => '☀️', '3' => '🌙'];
                                        $shiftTime = ['1' => '05.00–12.00', '2' => '12.00–19.00', '3' => '19.00–02.00'];
                                        @endphp

                                        <span class="badge bg-{{ $user->isShiftActive() ? 'primary' : 'dark' }}">
                                            {{ $shiftIcon[$user->shift] }} Shift {{ $shiftLabel[$user->shift] }}
                                        </span>

                                        <small class="text-muted d-block" style="font-size:11px">
                                            {{ $shiftTime[$user->shift] }}
                                        </small>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- STATUS REALTIME --}}
                                    <td>
                                        <span class="badge bg-{{ $user->effective_status === 'aktif' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($user->effective_status) }}
                                        </span>

                                        @if($user->status_override)
                                        <span class="badge bg-warning text-dark ms-1">
                                            <i class="fa fa-hand-pointer"></i> Manual
                                        </span>
                                        @elseif($user->shift)
                                        <small class="text-muted d-block" style="font-size:11px">
                                            <i class="fa fa-clock"></i> Otomatis
                                        </small>
                                        @endif
                                    </td>

                                    <td>{{ $user->created_at?->format('d M Y') ?? '-' }}</td>

                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal{{ $user->id_user }}">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        @if($user->id_user !== auth()->user()->id_user)
                                        <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin hapus user?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
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
                        placeholder="Min. 6 karakter" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" id="addRole" class="form-select bg-dark border-0 text-white" required>
                            <option value="">— Pilih —</option>
                            <option value="admin" {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="owner" {{ old('role') === 'owner'   ? 'selected' : '' }}>Owner</option>
                        </select>
                    </div>
                    <div class="col-6" id="addShiftWrapper" style="display:none">
                        <label class="form-label">Shift</label>
                        <select name="shift" id="addShift" class="form-select bg-dark border-0 text-white">
                            <option value="">— Tidak ada —</option>
                            <option value="1" {{ old('shift') === '1' ? 'selected' : '' }}>🌅 Pagi (05.00–12.00)</option>
                            <option value="2" {{ old('shift') === '2' ? 'selected' : '' }}>☀️ Siang (12.00–19.00)</option>
                            <option value="3" {{ old('shift') === '3' ? 'selected' : '' }}>🌙 Malam (19.00–02.00)</option>
                        </select>
                    </div>
                </div>

                {{-- Status: muncul full kalau tidak ada shift, atau sebagai override kalau ada shift --}}
                <div id="addStatusWrapper">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label class="form-label mb-0">Status <span class="text-danger">*</span></label>
                        <div id="addOverrideToggleWrapper" class="d-none">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="status_override"
                                    id="addStatusOverride" {{ old('status_override') ? 'checked' : '' }}>
                                <label class="form-check-label small text-warning" for="addStatusOverride">
                                    Override manual
                                </label>
                            </div>
                        </div>
                    </div>
                    <select name="status" id="addStatus" class="form-select bg-dark border-0 text-white" required>
                        <option value="aktif" {{ old('status') !== 'nonaktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <small id="addStatusHint" class="text-muted d-none mt-1 d-block">
                        <i class="fa fa-info-circle"></i> Status akan otomatis mengikuti jam shift. Aktifkan override untuk set manual.
                    </small>
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
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" id="editRole{{ $user->id_user }}"
                            class="form-select bg-dark border-0 text-white"
                            data-userid="{{ $user->id_user }}" required>
                            <option value="admin" {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ $user->role === 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="owner" {{ $user->role === 'owner'   ? 'selected' : '' }}>Owner</option>
                        </select>
                    </div>
                    <div class="col-6" id="editShiftWrapper{{ $user->id_user }}"
                        style="{{ $user->role !== 'petugas' ? 'display:none' : '' }}">
                        <label class="form-label">Shift</label>
                        <select name="shift" id="editShift{{ $user->id_user }}"
                            class="form-select bg-dark border-0 text-white"
                            data-userid="{{ $user->id_user }}">
                            <option value="">— Tidak ada —</option>
                            <option value="1" {{ $user->shift === '1' ? 'selected' : '' }}>🌅 Pagi (05.00–12.00)</option>
                            <option value="2" {{ $user->shift === '2' ? 'selected' : '' }}>☀️ Siang (12.00–19.00)</option>
                            <option value="3" {{ $user->shift === '3' ? 'selected' : '' }}>🌙 Malam (19.00–02.00)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label class="form-label mb-0">Status <span class="text-danger">*</span></label>
                        <div id="editOverrideToggleWrapper{{ $user->id_user }}"
                            class="{{ $user->shift ? '' : 'd-none' }}">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="status_override"
                                    id="editStatusOverride{{ $user->id_user }}"
                                    {{ $user->status_override ? 'checked' : '' }}>
                                <label class="form-check-label small text-warning" for="editStatusOverride{{ $user->id_user }}">
                                    Override manual
                                </label>
                            </div>
                        </div>
                    </div>
                    <select name="status" id="editStatus{{ $user->id_user }}"
                        class="form-select bg-dark border-0 text-white"
                        {{ ($user->shift && !$user->status_override) ? 'disabled' : '' }}
                        required>
                        <option value="aktif" {{ $user->status === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $user->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <small id="editStatusHint{{ $user->id_user }}"
                        class="text-muted mt-1 d-block {{ $user->shift && !$user->status_override ? '' : 'd-none' }}"
                        style="{{ $user->shift && !$user->status_override ? '' : 'display:none!important' }}">
                        <i class="fa fa-info-circle"></i> Status otomatis mengikuti jam shift. Aktifkan override untuk set manual.
                    </small>
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

    // =============================================
    // MODAL TAMBAH — shift hanya muncul kalau role = petugas
    // =============================================
    const addRole = document.getElementById('addRole');
    const addShiftWrapper = document.getElementById('addShiftWrapper');
    const addShift = document.getElementById('addShift');
    const addStatus = document.getElementById('addStatus');
    const addStatusOverride = document.getElementById('addStatusOverride');
    const addOverrideWrapper = document.getElementById('addOverrideToggleWrapper');
    const addStatusHint = document.getElementById('addStatusHint');

    function syncAddShiftVisibility() {
        const isPetugas = addRole.value === 'petugas';
        addShiftWrapper.style.display = isPetugas ? '' : 'none';
        if (!isPetugas) {
            addShift.value = ''; // reset shift kalau bukan petugas
        }
        syncAddStatus();
    }

    function syncAddStatus() {
        const hasShift = addShift.value !== '' && addRole.value === 'petugas';
        addOverrideWrapper.classList.toggle('d-none', !hasShift);
        addStatusHint.classList.toggle('d-none', !(hasShift && !addStatusOverride.checked));
        addStatus.disabled = hasShift && !addStatusOverride.checked;
    }

    addRole.addEventListener('change', syncAddShiftVisibility);
    addShift.addEventListener('change', syncAddStatus);
    addStatusOverride.addEventListener('change', syncAddStatus);
    syncAddShiftVisibility();

    // =============================================
    // MODAL EDIT — shift hanya muncul kalau role = petugas
    // =============================================
    document.querySelectorAll('[id^="editRole"]').forEach(roleEl => {
        const uid = roleEl.dataset.userid;
        const shiftWrapper = document.getElementById('editShiftWrapper' + uid);
        const shiftEl = document.getElementById('editShift' + uid);
        const statusEl = document.getElementById('editStatus' + uid);
        const overrideEl = document.getElementById('editStatusOverride' + uid);
        const overrideWrapper = document.getElementById('editOverrideToggleWrapper' + uid);
        const hintEl = document.getElementById('editStatusHint' + uid);

        function syncEditShiftVisibility() {
            const isPetugas = roleEl.value === 'petugas';
            shiftWrapper.style.display = isPetugas ? '' : 'none';
            if (!isPetugas && shiftEl) shiftEl.value = '';
            syncEditStatus();
        }

        function syncEditStatus() {
            const hasShift = shiftEl && shiftEl.value !== '' && roleEl.value === 'petugas';
            if (overrideWrapper) overrideWrapper.classList.toggle('d-none', !hasShift);
            const isOverride = overrideEl ? overrideEl.checked : false;
            if (statusEl) statusEl.disabled = hasShift && !isOverride;
            if (hintEl) hintEl.style.display = (hasShift && !isOverride) ? '' : 'none';
        }

        roleEl.addEventListener('change', syncEditShiftVisibility);
        if (shiftEl) shiftEl.addEventListener('change', syncEditStatus);
        if (overrideEl) overrideEl.addEventListener('change', syncEditStatus);
        syncEditShiftVisibility();
    });
</script>
@endpush