@extends('layouts.owner')

@section('title', 'Pesanan Masuk')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Pesanan Sewa Masuk</h2>
            <p class="text-muted small mb-0">Kelola dan tinjau permintaan sewa kamar dari pencari kost di properti Anda.</p>
        </div>
        <div>
            <a href="{{ route('owner.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Status Tabs Nav -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-2">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a href="{{ route('owner.bookings.index', array_merge(request()->except(['status', 'page']))) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ empty($status) ? 'active bg-dark text-white' : 'text-secondary' }}">
                    Semua Pesanan <span class="badge bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('owner.bookings.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-secondary' }}">
                    <i class="fa-solid fa-clock me-1 text-warning"></i> Menunggu Konfirmasi <span class="badge bg-dark-subtle text-dark ms-1">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('owner.bookings.index', array_merge(request()->except(['page']), ['status' => 'approved'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'approved' ? 'active bg-success text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-check me-1 text-success"></i> Disetujui <span class="badge bg-light text-dark ms-1">{{ $counts['approved'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('owner.bookings.index', array_merge(request()->except(['page']), ['status' => 'rejected'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'rejected' ? 'active bg-danger text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Ditolak <span class="badge bg-light text-dark ms-1">{{ $counts['rejected'] }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('owner.bookings.index') }}" class="row g-2 align-items-center">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama pencari kost, no telepon, atau nama properti..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-burnt flex-grow-1">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if($search || $status)
                    <a href="{{ route('owner.bookings.index') }}" class="btn btn-light border px-3" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Pesanan -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="ps-4">ID Booking</th>
                        <th>Pencari Kost</th>
                        <th>Kost Dipesan</th>
                        <th>Mulai Sewa</th>
                        <th>Durasi</th>
                        <th>Total Biaya</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="ps-4 fw-bold text-muted fs-xs">#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light border fw-bold text-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($booking->user->name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark small">{{ $booking->user->name ?? 'Pencari Kost' }}</div>
                                    <div class="text-muted fs-xs">{{ $booking->user->phone ?? $booking->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($booking->kost && $booking->kost->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $booking->kost->images->first()->image_path) }}" alt="{{ $booking->kost->name }}" class="rounded-2 object-fit-cover" width="36" height="36">
                                @endif
                                <div>
                                    <div class="fw-semibold small text-dark">{{ $booking->kost->name ?? 'Kost Dihapus' }}</div>
                                    <span class="text-muted fs-xs">Sisa {{ $booking->kost->available_rooms ?? 0 }} Kamar</span>
                                </div>
                            </div>
                        </td>
                        <td class="small fw-semibold text-dark">
                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border small">{{ $booking->duration_months }} Bulan</span>
                        </td>
                        <td class="fw-bold small text-dark">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </td>
                        <td class="text-muted fs-xs">
                            {{ $booking->created_at->format('d/m/Y') }}<br>
                            {{ $booking->created_at->format('H:i') }}
                        </td>
                        <td>
                            <x-admin.status-badge :status="$booking->status" />
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('owner.bookings.show', $booking->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Lihat Detail Pesanan">
                                    <i class="fa-solid fa-eye text-secondary"></i>
                                </a>
                                @if($booking->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#approveBookingModal{{ $booking->id }}" title="Setujui Pesanan">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#rejectBookingModal{{ $booking->id }}" title="Tolak Pesanan">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @endif
                            </div>

                            @if($booking->status === 'pending')
                            <!-- Modal Setujui Booking -->
                            <div class="modal fade" id="approveBookingModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered text-start">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold text-success">
                                                <i class="fa-solid fa-circle-check me-1"></i> Setujui Pengajuan Sewa
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            Apakah Anda yakin ingin menyetujui pesanan dari <strong>{{ $booking->user->name ?? 'Pencari' }}</strong> untuk kost <strong>{{ $booking->kost->name ?? '' }}</strong>?
                                            <div class="alert alert-light border small mt-2 mb-0">
                                                <i class="fa-solid fa-circle-info text-primary me-1"></i>
                                                Sisa kamar kosong pada kost ini akan otomatis berkurang 1 (sisa saat ini: {{ $booking->kost->available_rooms }} kamar).
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('owner.bookings.update', $booking->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success rounded-pill px-3 fw-bold">Ya, Setujui Pesanan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tolak Booking -->
                            <div class="modal fade" id="rejectBookingModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered text-start">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold text-danger">
                                                <i class="fa-solid fa-circle-xmark me-1"></i> Tolak Pengajuan Sewa
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            Yakin ingin menolak pengajuan booking dari <strong>{{ $booking->user->name ?? 'Pencari' }}</strong>?
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('owner.bookings.update', $booking->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Tolak Pesanan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="py-4 text-muted">
                                <i class="fa-solid fa-calendar-xmark fs-2 mb-2 opacity-50"></i>
                                <h6 class="fw-bold text-dark">Tidak Ada Pesanan Ditemukan</h6>
                                <p class="small mb-0">Belum ada pengajuan sewa atau filter pencarian tidak sesuai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="card-footer bg-white border-top py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} dari {{ $bookings->total() }} pesanan
                </div>
                <div>
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
