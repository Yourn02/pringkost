@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Manajemen Pengguna & Otoritas</h2>
            <p class="text-muted small mb-0">Kelola pengguna platform PringKost beserta status akun dan hak akses.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Role Filter Tabs (Hanya Pemilik dan Pencari, Admin tidak ditampilkan) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-2">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a href="{{ route('admin.users.index', array_merge(request()->except(['role', 'page']))) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ empty($role) ? 'active bg-dark text-white' : 'text-secondary' }}">
                    Semua Pengguna <span class="badge bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index', array_merge(request()->except(['page']), ['role' => 'pemilik'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $role === 'pemilik' ? 'active bg-primary text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-user-tie me-1"></i> Pemilik Kost <span class="badge bg-light text-dark ms-1">{{ $counts['pemilik'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index', array_merge(request()->except(['page']), ['role' => 'pencari'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $role === 'pencari' ? 'active bg-info text-dark' : 'text-secondary' }}">
                    <i class="fa-solid fa-magnifying-glass-location me-1"></i> Pencari Kost <span class="badge bg-light text-dark ms-1">{{ $counts['pencari'] }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            @if(!empty($role))
                <input type="hidden" name="role" value="{{ $role }}">
            @endif
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari berdasarkan nama, email, atau no telepon..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select bg-light">
                    <option value="">Semua Status Akun</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>Suspended (Ditangguhkan)</option>
                    <option value="blocked" {{ $status === 'blocked' ? 'selected' : '' }}>Blocked (Diblokir)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-burnt flex-grow-1">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                @if($search || $status || $role)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light border px-3" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Pengguna -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="ps-4">ID</th>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>No Telepon</th>
                        <th>Aktivitas Terkait</th>
                        <th>Status Akun</th>
                        <th>Bergabung</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4 fw-bold text-muted fs-xs">#{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-light border fw-bold text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 0.95rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $user->name }}
                                    </a>
                                    <div class="text-muted fs-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $roleBadge = match($user->role) {
                                    'pemilik' => 'bg-primary-subtle text-primary border-primary-subtle',
                                    default => 'bg-info-subtle text-info-emphasis border-info-subtle',
                                };
                                $roleLabel = match($user->role) {
                                    'pemilik' => 'Pemilik Kost',
                                    default => 'Pencari Kost',
                                };
                            @endphp
                            <span class="badge {{ $roleBadge }} border px-2.5 py-1 rounded-pill small fw-semibold">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="small text-secondary">
                            {{ $user->phone ?? '-' }}
                        </td>
                        <td class="small">
                            @if($user->role === 'pemilik')
                                <span class="badge bg-light text-dark border">{{ $user->kosts_count }} Kost</span>
                            @else
                                <span class="badge bg-light text-dark border">{{ $user->bookings_count }} Booking</span>
                            @endif
                        </td>
                        <td>
                            <x-admin.status-badge :status="$user->status ?? 'active'" />
                        </td>
                        <td class="text-muted fs-xs">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Lihat Profil Pengguna">
                                    <i class="fa-solid fa-eye text-secondary"></i>
                                </a>

                                <!-- Tindakan Status Akun Pengguna -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border rounded-pill px-2.5" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Ubah Status">
                                        <i class="fa-solid fa-ellipsis-vertical text-secondary"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-1">
                                        @if($user->status !== 'active')
                                            <li>
                                                <form action="{{ route('admin.users.status.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="dropdown-item text-success rounded-2 py-1.5 small">
                                                        <i class="fa-solid fa-circle-check me-2"></i> Aktifkan Akun
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        @if($user->status !== 'suspended')
                                            <li>
                                                <button type="button" class="dropdown-item text-warning-emphasis rounded-2 py-1.5 small" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $user->id }}">
                                                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Suspend Akun
                                                </button>
                                            </li>
                                        @endif
                                        @if($user->status !== 'blocked')
                                            <li>
                                                <button type="button" class="dropdown-item text-danger rounded-2 py-1.5 small" data-bs-toggle="modal" data-bs-target="#blockModal{{ $user->id }}">
                                                    <i class="fa-solid fa-ban me-2"></i> Blokir Akun
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Modal Suspend -->
                                <div class="modal fade" id="suspendModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered text-start">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-warning-emphasis"><i class="fa-solid fa-triangle-exclamation me-1"></i> Tangguhkan Akun</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                Yakin ingin menangguhkan akun <strong>{{ $user->name }}</strong>? Pengguna tidak akan dapat login atau melakukan transaksi untuk sementara.
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.users.status.update', $user->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="suspended">
                                                    <button type="submit" class="btn btn-warning rounded-pill px-3 fw-bold">Suspend Akun</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Block -->
                                <div class="modal fade" id="blockModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered text-start">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-ban me-1"></i> Blokir Akun Pengguna</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                Yakin ingin memblokir akun <strong>{{ $user->name }}</strong>? Akun ini akan dikunci dari platform dan dikeluarkan dari seluruh sesi login.
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.users.status.update', $user->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="blocked">
                                                    <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Blokir Akun</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-3 text-muted">
                                <i class="fa-solid fa-users fs-2 mb-2 opacity-50"></i>
                                <h6 class="fw-bold text-dark">Pengguna Tidak Ditemukan</h6>
                                <p class="small mb-0">Coba ubah kriteria pencarian atau filter role.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="card-footer bg-white border-top py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
