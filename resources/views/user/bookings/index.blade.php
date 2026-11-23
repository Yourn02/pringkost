@extends('layouts.user')

@section('title', 'Pemesanan Sewa Saya')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Riwayat Pemesanan Saya</h2>
            <p class="text-muted small mb-0">Pantau seluruh status pengajuan sewa kost yang telah Anda kirimkan kepada pemilik properti.</p>
        </div>
        <div>
            <a href="{{ route('user.kosts.index') }}" class="btn btn-burnt rounded-pill fw-semibold btn-sm px-3 shadow-xs">
                <i class="fa-solid fa-plus me-1"></i> Pesan Kost Lainnya
            </a>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="card border border-light-subtle shadow-xs rounded-4 mb-4 bg-white p-2">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a href="{{ route('user.bookings.index', array_merge(request()->except(['status', 'page']))) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ empty($status) ? 'active bg-dark text-white' : 'text-secondary' }}">
                    Semua Pemesanan <span class="badge bg-light text-dark ms-1">{{ $counts['all'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.bookings.index', array_merge(request()->except(['page']), ['status' => 'pending'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-secondary' }}">
                    <i class="fa-solid fa-clock me-1 text-warning"></i> Menunggu Konfirmasi <span class="badge bg-dark-subtle text-dark ms-1">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.bookings.index', array_merge(request()->except(['page']), ['status' => 'approved'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'approved' ? 'active bg-success text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-check me-1 text-success"></i> Disetujui <span class="badge bg-light text-dark ms-1">{{ $counts['approved'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.bookings.index', array_merge(request()->except(['page']), ['status' => 'rejected'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill small fw-semibold {{ $status === 'rejected' ? 'active bg-danger text-white' : 'text-secondary' }}">
                    <i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Ditolak <span class="badge bg-light text-dark ms-1">{{ $counts['rejected'] }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Search Toolbar -->
    <div class="card border border-light-subtle shadow-xs rounded-4 mb-4 bg-white p-3">
        <form method="GET" action="{{ route('user.bookings.index') }}" class="row g-2 align-items-center">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari berdasarkan nama kost atau alamat..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-burnt flex-grow-1">
                    <i class="fa-solid fa-filter me-1"></i> Cari
                </button>
                @if($search || $status)
                    <a href="{{ route('user.bookings.index') }}" class="btn btn-light border px-3" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Pemesanan -->
    <div class="card border border-light-subtle shadow-xs rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="ps-4">Kode Booking</th>
                        <th>Kost Dipesan</th>
                        <th>Mulai Sewa</th>
                        <th>Durasi</th>
                        <th>Total Harga</th>
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
                            <div class="d-flex align-items-center gap-2.5">
                                @if($booking->kost && $booking->kost->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $booking->kost->images->first()->image_path) }}" alt="{{ $booking->kost->name }}" class="rounded-2 object-fit-cover shadow-xs" width="40" height="40">
                                @else
                                    <div class="rounded-2 bg-light text-muted d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark small">{{ $booking->kost->name ?? 'Kost Dihapus' }}</div>
                                    <div class="text-muted fs-xs">{{ $booking->kost->user->name ?? 'Pemilik' }}</div>
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
                            {{ $booking->created_at->format('d/m/Y, H:i') }}
                        </td>
                        <td>
                            <x-admin.status-badge :status="$booking->status" />
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('user.bookings.show', $booking->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-secondary"></i>
                                </a>
                                @if($booking->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}" title="Batalkan Pengajuan">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @endif
                            </div>

                            @if($booking->status === 'pending')
                            <!-- Modal Batalkan Booking -->
                            <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered text-start">
                                    <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header border-bottom">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Batalkan Pengajuan Sewa
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                Apakah Anda yakin ingin membatalkan pengajuan sewa untuk <strong>{{ $booking->kost->name ?? 'Kost' }}</strong>?
                                                <p class="text-muted small mt-2 mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Ya, Batalkan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="py-4 text-muted">
                                <i class="fa-solid fa-calendar-xmark fs-2 mb-2 opacity-50"></i>
                                <h6 class="fw-bold text-dark">Tidak Ada Pemesanan Ditemukan</h6>
                                <p class="small mb-3">Kamu belum memiliki riwayat pemesanan sewa kost.</p>
                                <a href="{{ route('user.kosts.index') }}" class="btn btn-burnt btn-sm rounded-pill px-3">
                                    Mulai Cari Kost
                                </a>
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
                    Menampilkan {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} dari {{ $bookings->total() }} pemesanan
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
