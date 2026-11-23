@extends('layouts.owner')

@section('title', 'Manajemen Kost Saya')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Manajemen Properti Kost</h2>
            <p class="text-muted small mb-0">Kelola informasi listing kost Anda, perbarui tarif, dan pantau status verifikasi administrator.</p>
        </div>
        <div>
            <a href="{{ route('owner.kosts.create') }}" class="btn btn-burnt rounded-pill fw-bold btn-sm px-3 shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Tambah Kost Baru
            </a>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-2">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a href="{{ route('owner.kosts.index', array_merge(request()->except(['status', 'page']))) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ empty($status) ? 'active bg-dark text-white' : 'text-secondary' }}">
                    Semua Kost <span class="badge bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('owner.kosts.index', array_merge(request()->except(['page']), ['status' => 'approved'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'approved' ? 'active bg-success text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-check me-1 text-success"></i> Disetujui <span class="badge bg-light text-dark ms-1">{{ $counts['approved'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('owner.kosts.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-secondary' }}">
                    <i class="fa-solid fa-clock me-1 text-warning"></i> Menunggu Verifikasi <span class="badge bg-dark-subtle text-dark ms-1">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('owner.kosts.index', array_merge(request()->except(['page']), ['status' => 'rejected'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'rejected' ? 'active bg-danger text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Ditolak <span class="badge bg-light text-dark ms-1">{{ $counts['rejected'] }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Filter, Search & Per-Page Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('owner.kosts.index') }}" class="row g-2 align-items-center">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama properti kost atau alamat..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted text-nowrap">Tampilkan:</span>
                    <select name="per_page" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 Kost</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 Kost</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 Kost</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 Kost</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-burnt flex-grow-1">
                    <i class="fa-solid fa-filter me-1"></i> Terapkan
                </button>
                @if($search || $status || $perPage != 10)
                    <a href="{{ route('owner.kosts.index') }}" class="btn btn-light border px-3" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Kost Pemilik -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Foto & Properti Kost</th>
                        <th>Jenis</th>
                        <th>Harga / Bulan</th>
                        <th>Alamat</th>
                        <th>Kapasitas Kamar</th>
                        <th>Status Verifikasi</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kosts as $index => $kost)
                    <tr>
                        <td class="ps-4 fw-bold text-muted small">
                            {{ $kosts->firstItem() + $index }}
                        </td>
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
                                    <a href="{{ route('owner.kosts.show', $kost->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $kost->name }}
                                    </a>
                                    <div class="text-muted fs-xs">
                                        {{ $kost->bookings_count ?? $kost->bookings->count() }} pesanan masuk
                                    </div>
                                </div>
                            </div>
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
                            <div class="small fw-semibold text-dark">{{ $kost->available_rooms }} / {{ $kost->total_rooms }} Kamar</div>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle fs-xs">
                                {{ $kost->available_rooms > 0 ? 'Tersedia' : 'Penuh' }}
                            </span>
                        </td>
                        <td>
                            <x-admin.status-badge :status="$kost->status" />
                            @if($kost->status === 'rejected' && $kost->rejection_reason)
                                <div class="text-danger fs-xs mt-1 text-truncate" style="max-width: 150px;" title="{{ $kost->rejection_reason }}">
                                    Alasan: {{ $kost->rejection_reason }}
                                </div>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('owner.kosts.show', $kost->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-secondary"></i>
                                </a>
                                <a href="{{ route('owner.kosts.edit', $kost->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Edit Data Properti">
                                    <i class="fa-solid fa-pen-to-square text-secondary"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#deleteKostModal{{ $kost->id }}" title="Hapus Kost">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <!-- Modal Konfirmasi Hapus Kost -->
                            <div class="modal fade" id="deleteKostModal{{ $kost->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered text-start">
                                    <form action="{{ route('owner.kosts.destroy', $kost->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Hapus Properti Kost
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                Apakah Anda yakin ingin menghapus properti <strong>{{ $kost->name }}</strong>?
                                                <p class="text-muted small mt-2 mb-0">Perhatian: Properti yang memiliki pesanan sewa aktif tidak dapat dihapus demi keamanan riwayat data transaksi.</p>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Ya, Hapus Properti</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4 text-muted">
                                <i class="fa-solid fa-hotel fs-1 mb-2 opacity-50"></i>
                                <h6 class="fw-bold text-dark">Tidak Ada Data Kost Ditemukan</h6>
                                <p class="small mb-3">Anda belum menambahkan kost atau filter pencarian tidak sesuai.</p>
                                <a href="{{ route('owner.kosts.create') }}" class="btn btn-burnt btn-sm rounded-pill px-3">
                                    <i class="fa-solid fa-plus me-1"></i> Tambah Kost Sekarang
                                </a>
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
