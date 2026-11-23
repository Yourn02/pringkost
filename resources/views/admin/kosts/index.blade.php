@extends('layouts.admin')

@section('title', 'Manajemen Kost')

@section('content')
<div class="container-fluid px-0">
    <!-- Header & Breadcrumbs Info -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Manajemen Listing Kost</h2>
            <p class="text-muted small mb-0">Kelola direktori properti kost se-Pringsewu, tinjau legalitas, dan tentukan status publikasi.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Status Tabs Nav -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-2">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a href="{{ route('admin.kosts.index', array_merge(request()->except(['status', 'page']))) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ empty($status) ? 'active bg-dark text-white' : 'text-secondary' }}">
                    Semua Kost <span class="badge bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kosts.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-secondary' }}">
                    <i class="fa-solid fa-clock me-1 text-warning"></i> Menunggu Verifikasi <span class="badge bg-dark-subtle text-dark ms-1">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kosts.index', array_merge(request()->except(['page']), ['status' => 'approved'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'approved' ? 'active bg-success text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-check me-1 text-success"></i> Disetujui <span class="badge bg-light text-dark ms-1">{{ $counts['approved'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kosts.index', array_merge(request()->except(['page']), ['status' => 'rejected'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'rejected' ? 'active bg-danger text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Ditolak <span class="badge bg-light text-dark ms-1">{{ $counts['rejected'] }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('admin.kosts.index') }}" class="row g-2 align-items-center">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama kost, alamat, atau nama pemilik..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select bg-light">
                    <option value="">Semua Tipe Kost</option>
                    <option value="putra" {{ $type === 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                    <option value="putri" {{ $type === 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                    <option value="campur" {{ $type === 'campur' ? 'selected' : '' }}>Campur</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-burnt flex-grow-1">
                    <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                </button>
                @if($search || $type || $status)
                    <a href="{{ route('admin.kosts.index') }}" class="btn btn-light border px-3" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Kost -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="ps-4">ID</th>
                        <th>Kost & Galeri</th>
                        <th>Pemilik</th>
                        <th>Jenis</th>
                        <th>Harga / Bulan</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Pengajuan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kosts as $kost)
                    <tr>
                        <td class="ps-4 fw-bold text-muted fs-xs">#{{ $kost->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                @if($kost->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $kost->images->first()->image_path) }}" alt="{{ $kost->name }}" class="rounded-3 object-fit-cover shadow-xs" width="48" height="48">
                                @else
                                    <div class="rounded-3 bg-light text-muted d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.kosts.show', $kost->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $kost->name }}
                                    </a>
                                    <div class="text-muted fs-xs">
                                        {{ $kost->available_rooms }} / {{ $kost->total_rooms }} kamar tersedia
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold small text-dark">{{ $kost->user->name ?? 'Tidak Ada' }}</div>
                            <div class="text-muted fs-xs">{{ $kost->user->phone ?? $kost->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border text-capitalize small">
                                {{ $kost->type }}
                            </span>
                        </td>
                        <td class="fw-bold small text-dark">
                            Rp {{ number_format($kost->price, 0, ',', '.') }}
                        </td>
                        <td>
                            <div class="text-muted small text-truncate" style="max-width: 170px;" title="{{ $kost->address }}">
                                {{ $kost->address }}
                            </div>
                        </td>
                        <td>
                            <x-admin.status-badge :status="$kost->status" />
                            @if($kost->status === 'rejected' && $kost->rejection_reason)
                                <div class="text-danger fs-xs mt-1 text-truncate" style="max-width: 130px;" title="{{ $kost->rejection_reason }}">
                                    Alasan: {{ $kost->rejection_reason }}
                                </div>
                            @endif
                        </td>
                        <td class="text-muted fs-xs">
                            {{ $kost->created_at->format('d/m/Y') }}<br>
                            {{ $kost->created_at->format('H:i') }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.kosts.show', $kost->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Lihat Detail & Foto">
                                    <i class="fa-solid fa-eye text-secondary"></i>
                                </a>
                                @if($kost->status !== 'approved')
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#approveModal{{ $kost->id }}" title="Setujui">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                @endif
                                @if($kost->status !== 'rejected')
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $kost->id }}" title="Tolak">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- Modal Approve -->
                            <div class="modal fade" id="approveModal{{ $kost->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered text-start">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold text-success">
                                                <i class="fa-solid fa-circle-check me-1"></i> Setujui Listing Kost
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            Apakah Anda yakin ingin menyetujui listing <strong>{{ $kost->name }}</strong>?
                                            <div class="alert alert-light border small mt-2 mb-0">
                                                Status akan berubah menjadi <strong>Approved</strong> dan listing kost akan otomatis tayang di halaman pencarian publik PringKost.
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.kost.approve', $kost->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success rounded-pill px-3 fw-bold">Ya, Setujui</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Reject -->
                            <div class="modal fade" id="rejectModal{{ $kost->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered text-start">
                                    <form action="{{ route('admin.kost.reject', $kost->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="fa-solid fa-circle-xmark me-1"></i> Tolak Listing Kost
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <p class="mb-2">Anda akan menolak pengajuan properti <strong>{{ $kost->name }}</strong>.</p>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-dark">Alasan Penolakan</label>
                                                    <textarea name="rejection_reason" class="form-control rounded-3" rows="3" placeholder="Sebutkan alasan penolakan agar pemilik kost dapat memperbaiki informasinya..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Tolak Listing</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="py-4 text-muted">
                                <i class="fa-solid fa-magnifying-glass fs-2 mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold text-dark">Tidak Ada Data Listing Kost Ditemukan</h6>
                                <p class="small mb-0">Coba ubah filter pencarian atau kembali ke daftar semua kost.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kosts->hasPages())
        <div class="card-footer bg-white border-top py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $kosts->firstItem() }} - {{ $kosts->lastItem() }} dari {{ $kosts->total() }} kost
                </div>
                <div>
                    {{ $kosts->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
