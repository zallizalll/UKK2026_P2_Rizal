@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid pt-4 px-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Log Aktivitas</h4>
    </div>

    <div class="bg-secondary rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th style="width:46px">#</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                        <td style="white-space:nowrap">
                            {{ \Carbon\Carbon::parse($log->waktu_aktivitas)->format('d M Y, H:i') }}
                        </td>
                        <td>{{ $log->user->name ?? '<i class="text-muted">Dihapus</i>' }}</td>
                        <td>
                            @if($log->user)
                            @php
                            $warna = match($log->user->role) {
                            'admin' => 'danger',
                            'owner' => 'warning',
                            'petugas' => 'info',
                            default => 'secondary'
                            };
                            @endphp
                            <span class="badge bg-{{ $warna }}">{{ ucfirst($log->user->role) }}</span>
                            @else
                            <span class="badge bg-secondary">—</span>
                            @endif
                        </td>
                        <td>{{ $log->aktivitas }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fa fa-list fa-2x mb-2 d-block text-muted"></i>
                            Belum ada log aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($logs->hasPages())
        <div class="d-flex justify-content-end mt-3">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
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