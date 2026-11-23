@extends('layouts.user')

@section('title', 'Dashboard Pencari Kost')

@section('content')
<div class="container-fluid px-0">
    <!-- Welcome Section -->
    <div class="card border border-light-subtle shadow-xs rounded-4 p-4 mb-4 bg-white position-relative overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-1 text-dark">Halo, {{ Auth::user()->name }}!</h2>
                <p class="text-muted mb-0 small">
                    Temukan kost yang nyaman, aman, dan sesuai dengan kebutuhanmu di Pringsewu bersama PringKost.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('user.kosts.index') }}" class="btn btn-burnt rounded-3 fw-semibold btn-sm px-3 shadow-xs">
                        <i class="fa-solid fa-magnifying-glass-location me-1"></i> Mulai Cari Kost
                    </a>
                    <a href="{{ route('user.favorites.index') }}" class="btn btn-outline-secondary rounded-3 fw-semibold btn-sm px-3">
                        <i class="fa-solid fa-heart me-1 text-danger"></i> Kost Favorit ({{ $favoriteCount }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Kartu Statistik Aktivitas Pengguna (Clean & Minimalist) -->
    <div class="row g-3 mb-4">
        <!-- 1. Kost Favorit -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Kost Favorit" 
                :value="$favoriteCount" 
                icon="fa-heart" 
                color="burnt"
                link="{{ route('user.favorites.index') }}"
                description="Kost yang Anda simpan di wishlist"
            />
        </div>

        <!-- 2. Total Booking -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Total Booking" 
                :value="$totalBookings" 
                icon="fa-calendar-check" 
                color="dark"
                link="{{ route('user.bookings.index') }}"
                description="Semua riwayat pengajuan sewa Anda"
            />
        </div>

        <!-- 3. Menunggu Konfirmasi -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Menunggu" 
                :value="$pendingBookings" 
                icon="fa-clock" 
                color="warning"
                link="{{ route('user.bookings.index', ['status' => 'pending']) }}"
                description="Menunggu persetujuan pemilik kost"
                :badge="$pendingBookings > 0 ? 'Dalam Proses' : 'Tidak Ada'"
            />
        </div>

        <!-- 4. Diterima / Disetujui -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Diterima" 
                :value="$approvedBookings" 
                icon="fa-circle-check" 
                color="success"
                link="{{ route('user.bookings.index', ['status' => 'approved']) }}"
                description="Pengajuan sewa yang telah disetujui"
            />
        </div>
    </div>

    <!-- Section: Rekomendasi Kost & Aktivitas Pemesanan Terbaru -->
    <div class="row g-4 mb-4">
        <!-- Kolom Kiri: Rekomendasi Kost -->
        <div class="col-xl-8">
            <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Rekomendasi Kost Pilihan</h5>
                        <p class="text-muted small mb-0">Pilihan properti kost terverifikasi dengan kamar siap huni di Pringsewu.</p>
                    </div>
                    <a href="{{ route('user.kosts.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        Lihat Semua
                    </a>
                </div>

                <div class="row g-3">
                    @forelse($recommendedKosts as $kost)
                        <div class="col-md-6">
                            <div class="card h-100 border border-light-subtle rounded-4 overflow-hidden shadow-xs hover-shadow transition-all">
                                <div class="position-relative" style="height: 170px; background-color: #f3f4f6;">
                                    @if($kost->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $kost->images->first()->image_path) }}" alt="{{ $kost->name }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                            <i class="fa-solid fa-image fs-1 opacity-50"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-white text-dark border text-capitalize small shadow-xs">
                                            {{ $kost->type }}
                                        </span>
                                    </div>

                                    <!-- Tombol Favorit Toggle -->
                                    <form action="{{ route('user.favorites.toggle', $kost->id) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-white rounded-circle p-2 shadow-xs border-0 bg-white" title="{{ in_array($kost->id, $userWishlistIds) ? 'Hapus dari Favorit' : 'Tambah ke Favorit' }}">
                                            <i class="{{ in_array($kost->id, $userWishlistIds) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-secondary' }}"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $kost->name }}">{{ $kost->name }}</h6>
                                    <p class="text-muted fs-xs text-truncate mb-2" title="{{ $kost->address }}">
                                        <i class="fa-solid fa-location-dot me-1 text-secondary"></i> {{ $kost->address }}
                                    </p>
                                    
                                    <!-- Fasilitas Ringkas -->
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        @foreach($kost->facilities->take(3) as $fac)
                                            <span class="badge bg-light text-secondary border fs-xs py-1">{{ $fac->name }}</span>
                                        @endforeach
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                        <div>
                                            <div class="fw-bold text-dark small">
                                                Rp {{ number_format($kost->price, 0, ',', '.') }}<span class="text-muted fw-normal">/bln</span>
                                            </div>
                                            <span class="fs-xs text-muted">Sisa {{ $kost->available_rooms }} Kamar</span>
                                        </div>
                                        <a href="{{ route('user.kosts.show', $kost->id) }}" class="btn btn-sm btn-burnt rounded-pill px-3 fs-xs">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="py-3 text-muted">
                                <i class="fa-solid fa-hotel fs-1 mb-2 opacity-50"></i>
                                <h6 class="fw-bold text-dark">Belum Ada Rekomendasi Kost</h6>
                                <p class="small mb-0">Belum ada properti kost yang tersedia saat ini.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Aktivitas Pemesanan Terbaru -->
        <div class="col-xl-4">
            <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-4 h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-dark mb-0 small text-uppercase" style="letter-spacing: 0.5px;">
                        <i class="fa-solid fa-receipt me-1 text-muted"></i> Pemesanan Terbaru
                    </h6>
                    <a href="{{ route('user.bookings.index') }}" class="fs-xs text-decoration-none text-muted">Lihat Semua</a>
                </div>

                <div class="list-group list-group-flush flex-grow-1">
                    @forelse($recentBookings as $booking)
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-0 text-truncate small" title="{{ $booking->kost->name ?? 'Kost' }}">
                                        {{ $booking->kost->name ?? 'Kost Dihapus' }}
                                    </h6>
                                    <div class="text-muted fs-xs">Diajukan: {{ $booking->created_at->format('d M Y') }}</div>
                                </div>
                                <x-admin.status-badge :status="$booking->status" />
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-2 pt-1">
                                <div class="small fw-semibold text-dark">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                <a href="{{ route('user.bookings.show', $booking->id) }}" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 fs-xs">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 my-auto text-muted">
                            <i class="fa-solid fa-calendar-xmark fs-2 mb-2 opacity-50"></i>
                            <h6 class="fw-bold text-dark small">Belum Ada Pemesanan</h6>
                            <p class="fs-xs mb-3">Kamu belum pernah mengajukan sewa kost.</p>
                            <a href="{{ route('user.kosts.index') }}" class="btn btn-burnt btn-sm rounded-pill px-3 fs-xs">
                                Cari Kost Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pintasan Cepat -->
                <div class="mt-auto pt-3 border-top">
                    <h6 class="fw-bold text-dark mb-2 fs-xs text-uppercase" style="letter-spacing: 0.5px;">Pintasan Cepat</h6>
                    <div class="d-grid gap-1.5">
                        <a href="{{ route('user.kosts.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2 rounded-3">
                            <span class="fs-xs fw-semibold text-dark"><i class="fa-solid fa-magnifying-glass me-2 text-secondary"></i> Eksplorasi Katalog</span>
                            <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                        </a>
                        <a href="{{ route('user.favorites.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2 rounded-3">
                            <span class="fs-xs fw-semibold text-dark"><i class="fa-solid fa-heart me-2 text-danger"></i> Daftar Favorit</span>
                            <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
