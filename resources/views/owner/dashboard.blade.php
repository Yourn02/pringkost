@extends('layouts.owner')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="container-fluid px-0">
    <!-- Welcome Banner Section -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white position-relative overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-1 text-dark">Selamat datang, {{ Auth::user()->name }}!</h2>
                <p class="text-muted mb-0">
                    Kelola properti dan pantau aktivitas penyewaan kost Anda melalui PringKost.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('owner.kosts.create') }}" class="btn btn-burnt rounded-3 fw-bold btn-sm px-3 shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Kost
                    </a>
                    <a href="{{ route('owner.bookings.index') }}" class="btn btn-outline-dark rounded-3 fw-semibold btn-sm px-3">
                        <i class="fa-solid fa-calendar-check me-1"></i> Lihat Pesanan ({{ $incomingBookings }})
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Kartu Statistik Utama Dinamis -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Kost -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Total Kost" 
                :value="$totalKosts" 
                icon="fa-hotel" 
                color="burnt"
                link="{{ route('owner.kosts.index') }}"
                description="Total properti kost milik Anda"
            />
        </div>

        <!-- 2. Menunggu Verifikasi -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Menunggu Verifikasi" 
                :value="$pendingKosts" 
                icon="fa-clock" 
                color="amber"
                link="{{ route('owner.kosts.index', ['status' => 'pending']) }}"
                description="Menunggu peninjauan admin"
                :badge="$pendingKosts > 0 ? 'Dalam Antrean' : 'Semua Ditinjau'"
            />
        </div>

        <!-- 3. Pesanan Masuk (Pending) -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Pesanan Masuk" 
                :value="$incomingBookings" 
                icon="fa-calendar-check" 
                color="purple"
                link="{{ route('owner.bookings.index', ['status' => 'pending']) }}"
                description="Booking yang butuh konfirmasi Anda"
                :badge="$incomingBookings > 0 ? 'Perlu Respon' : 'Tidak Ada'"
            />
        </div>

        <!-- 4. Kamar Tersedia -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Kamar Tersedia" 
                :value="$availableRooms . ' / ' . $totalRooms" 
                icon="fa-door-open" 
                color="emerald"
                link="{{ route('owner.kosts.index') }}"
                description="Sisa kamar kosong siap huni"
            />
        </div>
    </div>

    <!-- Section Ringkasan Status Kost & Pesanan Terbaru -->
    <div class="row g-4 mb-4">
        <!-- Kolom Kiri: Pesanan Masuk Terbaru -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="p-2 rounded-3 bg-primary-subtle text-primary">
                            <i class="fa-solid fa-receipt"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Pesanan Sewa Terbaru</h5>
                            <span class="text-muted small">5 aktivitas pengajuan sewa terbaru pada properti kost Anda</span>
                        </div>
                    </div>
                    <a href="{{ route('owner.bookings.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-secondary small text-uppercase">
                                    <th class="ps-4">Pencari Kost</th>
                                    <th>Kost Tujuan</th>
                                    <th>Mulai Sewa & Durasi</th>
                                    <th>Total Biaya</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="rounded-circle bg-light border fw-bold text-dark d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                                {{ strtoupper(substr($booking->user->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $booking->user->name ?? 'Pengguna' }}</div>
                                                <div class="text-muted fs-xs">{{ $booking->user->phone ?? $booking->user->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small text-dark">{{ $booking->kost->name ?? 'Kost Dihapus' }}</div>
                                        <div class="text-muted fs-xs">Diajukan: {{ $booking->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</div>
                                        <span class="badge bg-light text-secondary border fs-xs">{{ $booking->duration_months }} Bulan</span>
                                    </td>
                                    <td class="fw-bold small text-dark">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <x-admin.status-badge :status="$booking->status" />
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('owner.bookings.show', $booking->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-3 text-muted">
                                            <i class="fa-solid fa-calendar-xmark fs-2 mb-2 opacity-50"></i>
                                            <h6 class="fw-bold text-dark">Belum Ada Pesanan Masuk</h6>
                                            <p class="small mb-0">Pesanan dari pencari kost akan langsung muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan Status Kost & Pintasan -->
        <div class="col-xl-4">
            <!-- Ringkasan Status Verifikasi Kost -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-pie me-2 text-warning"></i> Ringkasan Status Properti</h6>
                <div class="d-flex flex-column gap-2.5">
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-success-subtle border border-success-subtle">
                        <span class="small fw-bold text-success"><i class="fa-solid fa-circle-check me-2"></i> Kost Disetujui</span>
                        <span class="badge bg-success rounded-pill fs-xs">{{ $approvedKosts }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-warning-subtle border border-warning-subtle">
                        <span class="small fw-bold text-warning-emphasis"><i class="fa-solid fa-clock me-2"></i> Menunggu Verifikasi</span>
                        <span class="badge bg-warning text-dark rounded-pill fs-xs">{{ $pendingKosts }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-danger-subtle border border-danger-subtle">
                        <span class="small fw-bold text-danger"><i class="fa-solid fa-circle-xmark me-2"></i> Pengajuan Ditolak </span>
                        <span class="badge bg-danger rounded-pill fs-xs">{{ $rejectedKosts }}</span>
                    </div>
                </div>
            </div>

            <!-- Pintasan Cepat Mitra -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h6 class="fw-bold text-dark mb-3">Pintasan Cepat</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('owner.kosts.create') }}" class="btn btn-burnt text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-plus me-2"></i> Daftarkan Kost Baru</span>
                        <i class="fa-solid fa-chevron-right fs-xs"></i>
                    </a>
                    <a href="{{ route('owner.kosts.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-building-user me-2 text-primary"></i> Kelola Semua Kost</span>
                        <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                    </a>
                    <a href="{{ route('owner.bookings.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-calendar-check me-2 text-success"></i> Kelola Pesanan Masuk</span>
                        <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                    </a>
                    <a href="{{ route('owner.profile.edit') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-user-gear me-2 text-secondary"></i> Pengaturan Profil Akun</span>
                        <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Properti Terbaru Pemilik -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h5 class="fw-bold text-dark mb-1">Properti Kost Anda</h5>
                <p class="text-muted small mb-0">Daftar beberapa properti kost terbaru yang telah Anda daftarkan di platform PringKost.</p>
            </div>
            <a href="{{ route('owner.kosts.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                Lihat Semua Kost ({{ $totalKosts }})
            </a>
        </div>

        <div class="row g-3">
            @forelse($recentKosts as $kost)
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 border rounded-4 overflow-hidden shadow-xs hover-shadow transition-all">
                        <div class="position-relative" style="height: 160px; background-color: #eee;">
                            @if($kost->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $kost->images->first()->image_path) }}" alt="{{ $kost->name }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                    <i class="fa-solid fa-image fs-1 opacity-50"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-light text-dark border text-capitalize small">
                                    {{ $kost->type }}
                                </span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-2">
                                <x-admin.status-badge :status="$kost->status" />
                            </div>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $kost->name }}">{{ $kost->name }}</h6>
                            <p class="text-muted fs-xs text-truncate mb-2" title="{{ $kost->address }}"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $kost->address }}</p>
                            <div class="fw-bold text-dark small mb-3">
                                Rp {{ number_format($kost->price, 0, ',', '.') }}<span class="text-muted fw-normal">/bln</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                <span class="fs-xs text-muted">{{ $kost->available_rooms }} / {{ $kost->total_rooms }} Kamar</span>
                                <a href="{{ route('owner.kosts.show', $kost->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-2.5 fs-xs">
                                    Kelola
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-3 text-muted">
                        <i class="fa-solid fa-hotel fs-1 mb-2 opacity-50"></i>
                        <h6 class="fw-bold text-dark">Belum Ada Properti Kost</h6>
                        <p class="small mb-3">Mulai tambahkan listing kamar kost Anda agar dapat ditemukan oleh pencari kost di Pringsewu.</p>
                        <a href="{{ route('owner.kosts.create') }}" class="btn btn-burnt rounded-pill px-4 btn-sm">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Kost Sekarang
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
