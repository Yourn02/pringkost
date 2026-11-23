@extends('layouts.owner')

@section('title', 'Detail Properti: ' . $kost->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('owner.kosts.index') }}" class="btn btn-sm btn-light border rounded-pill px-2.5">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="h4 fw-bold text-dark mb-0">{{ $kost->name }}</h2>
                <x-admin.status-badge :status="$kost->status" />
            </div>
            <p class="text-muted small mb-0">Diajukan pada {{ $kost->created_at->format('d M Y, H:i') }} &bull; Sisa {{ $kost->available_rooms }} dari {{ $kost->total_rooms }} Kamar</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('owner.kosts.edit', $kost->id) }}" class="btn btn-burnt rounded-pill fw-semibold btn-sm px-3 shadow-sm">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Properti
            </a>
            @if($kost->status === 'approved')
                <a href="{{ route('kost.show', $kost->id) }}" target="_blank" class="btn btn-outline-dark rounded-pill btn-sm px-3">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat Halaman Publik
                </a>
            @endif
        </div>
    </div>

    @if($kost->status === 'rejected' && $kost->rejection_reason)
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation fs-3 text-danger"></i>
            <div>
                <h6 class="fw-bold mb-1">Pengajuan Kost Ditolak Administrator</h6>
                <div class="small">Alasan: {{ $kost->rejection_reason }}</div>
                <div class="fs-xs mt-1">Silakan klik tombol <strong>Edit Properti</strong> untuk memperbaiki data dan mengajukannya kembali.</div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Column -->
        <div class="col-lg-8">
            <!-- Galeri Foto -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-regular fa-images me-2 text-danger"></i> Galeri Foto Properti</h5>
                @if($kost->images->isNotEmpty())
                    <div class="row g-2">
                        @foreach($kost->images as $img)
                            <div class="col-md-4 col-6">
                                <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="img-fluid rounded-3 object-fit-cover w-100 shadow-xs" style="height: 180px;" alt="Foto Kost">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted bg-light rounded-3">
                        <i class="fa-solid fa-image fs-1 mb-2 opacity-50"></i>
                        <p class="small mb-0">Anda belum mengunggah foto untuk properti kost ini.</p>
                    </div>
                @endif
            </div>

            <!-- Deskripsi & Alamat -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">Deskripsi Lengkap</h5>
                <p class="text-secondary" style="white-space: pre-line; line-height: 1.7;">{{ $kost->description }}</p>

                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-2">Alamat & Lokasi</h5>
                <p class="text-secondary mb-0"><i class="fa-solid fa-location-dot me-2 text-danger"></i> {{ $kost->address }}</p>
            </div>

            <!-- Fasilitas -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">Fasilitas Kost</h5>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($kost->facilities as $fac)
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-check text-success me-1.5"></i> {{ $fac->name }}
                        </span>
                    @empty
                        <span class="text-muted small">Tidak ada fasilitas yang dipilih.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Side Column: Info Kamar & Riwayat Booking Kost Ini -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Tarif & Kapasitas</h6>
                <div class="mb-3">
                    <span class="text-muted small d-block">Harga Sewa Bulanan</span>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($kost->price, 0, ',', '.') }}</h3>
                </div>
                <div class="row g-2 border-top pt-3">
                    <div class="col-6">
                        <span class="text-muted small d-block">Jenis Kost</span>
                        <span class="fw-bold text-capitalize badge bg-light text-dark border">{{ $kost->type }}</span>
                    </div>
                    <div class="col-6">
                        <span class="text-muted small d-block">Kamar Kosong</span>
                        <span class="fw-bold text-dark">{{ $kost->available_rooms }} / {{ $kost->total_rooms }} Kamar</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h6 class="fw-bold text-dark mb-3">Pesanan Pada Kost Ini</h6>
                <div class="list-group list-group-flush small">
                    @forelse($kost->bookings as $booking)
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">{{ $booking->user->name ?? 'Pengguna' }}</div>
                                <div class="text-muted fs-xs">{{ $booking->duration_months }} Bulan &bull; {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</div>
                            </div>
                            <x-admin.status-badge :status="$booking->status" />
                        </div>
                    @empty
                        <div class="text-muted py-2">Belum ada pesanan untuk kost ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
