@extends('layouts.user')

@section('title', 'Cari & Eksplorasi Kost Pringsewu')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Katalog & Pencarian Kost</h2>
            <p class="text-muted small mb-0">Temukan kamar kost yang terverifikasi aman di sekitar Pringsewu sesuai kriteria dan anggaranmu.</p>
        </div>
        <div>
            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="card border border-light-subtle shadow-xs rounded-4 mb-4 bg-white p-3.5">
        <form method="GET" action="{{ route('user.kosts.index') }}">
            <div class="row g-2 mb-2">
                <!-- Search text -->
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama kost atau lokasi/alamat..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Tipe kost -->
                <div class="col-lg-3 col-sm-6">
                    <select name="type" class="form-select bg-light">
                        <option value="">Semua Tipe Kost</option>
                        <option value="putra" {{ request('type') === 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                        <option value="putri" {{ request('type') === 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                        <option value="campur" {{ request('type') === 'campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>

                <!-- Fasilitas -->
                <div class="col-lg-4 col-sm-6">
                    <select name="facility_id" class="form-select bg-light">
                        <option value="">Semua Fasilitas</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row g-2 align-items-center">
                <!-- Rentang Harga Min & Max -->
                <div class="col-lg-3 col-sm-6">
                    <input type="number" name="min_price" class="form-control bg-light" placeholder="Harga Min (Rp)" value="{{ request('min_price') }}" min="0">
                </div>
                <div class="col-lg-3 col-sm-6">
                    <input type="number" name="max_price" class="form-control bg-light" placeholder="Harga Max (Rp)" value="{{ request('max_price') }}" min="0">
                </div>

                <!-- Pengurutan (Sorting) -->
                <div class="col-lg-3 col-sm-6">
                    <select name="sort" class="form-select bg-light">
                        <option value="">Urutkan: Terbaru</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Termurah</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rooms_available" {{ request('sort') === 'rooms_available' ? 'selected' : '' }}>Kamar Tersedia Terbanyak</option>
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="col-lg-3 col-sm-6 d-flex gap-2">
                    <button type="submit" class="btn btn-burnt flex-grow-1">
                        <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                    </button>
                    @if(request()->anyFilled(['search', 'type', 'facility_id', 'min_price', 'max_price', 'sort']))
                        <a href="{{ route('user.kosts.index') }}" class="btn btn-light border px-3" title="Reset Semua Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Hasil Katalog Grid -->
    <div class="row g-3 mb-4">
        @forelse($kosts as $kost)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border border-light-subtle rounded-4 overflow-hidden shadow-xs hover-shadow transition-all bg-white">
                    <div class="position-relative" style="height: 190px; background-color: #f3f4f6;">
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

                        <!-- Wishlist Toggle Form -->
                        <form action="{{ route('user.favorites.toggle', $kost->id) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-white rounded-circle p-2 shadow-xs border-0 bg-white" title="{{ in_array($kost->id, $userWishlistIds) ? 'Hapus dari Favorit' : 'Tambah ke Favorit' }}">
                                <i class="{{ in_array($kost->id, $userWishlistIds) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-secondary' }}"></i>
                            </button>
                        </form>
                    </div>

                    <div class="card-body p-3.5 d-flex flex-column">
                        <div class="d-flex align-items-baseline justify-content-between gap-2 mb-1">
                            <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $kost->name }}">
                                <a href="{{ route('user.kosts.show', $kost->id) }}" class="text-dark text-decoration-none hover-underline">
                                    {{ $kost->name }}
                                </a>
                            </h6>
                            <span class="badge bg-light text-secondary border fs-xs text-nowrap">
                                {{ $kost->available_rooms > 0 ? 'Sisa ' . $kost->available_rooms . ' Kamar' : 'Penuh' }}
                            </span>
                        </div>

                        <p class="text-muted fs-xs text-truncate mb-2" title="{{ $kost->address }}">
                            <i class="fa-solid fa-location-dot me-1 text-secondary"></i> {{ $kost->address }}
                        </p>

                        <!-- Fasilitas Badges -->
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach($kost->facilities->take(3) as $fac)
                                <span class="badge bg-light text-secondary border fs-xs py-1">{{ $fac->name }}</span>
                            @endforeach
                            @if($kost->facilities->count() > 3)
                                <span class="badge bg-light text-muted border fs-xs py-1">+{{ $kost->facilities->count() - 3 }}</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                            <div>
                                <span class="text-muted fs-xs d-block">Tarif per Bulan</span>
                                <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($kost->price, 0, ',', '.') }}</h6>
                            </div>
                            <a href="{{ route('user.kosts.show', $kost->id) }}" class="btn btn-sm btn-burnt rounded-pill px-3 fs-xs fw-semibold">
                                Detail Kost
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="py-5 text-muted bg-white rounded-4 border border-light-subtle">
                    <i class="fa-solid fa-magnifying-glass fs-1 mb-2 opacity-50"></i>
                    <h5 class="fw-bold text-dark">Tidak Ada Kost yang Sesuai</h5>
                    <p class="small text-muted mb-3">Coba ubah kata kunci pencarian, rentang harga, atau reset filter Anda.</p>
                    <a href="{{ route('user.kosts.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-4">
                        Reset Filter
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($kosts->hasPages())
        <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <div class="small text-muted">
                    Menampilkan {{ $kosts->firstItem() }} - {{ $kosts->lastItem() }} dari total {{ $kosts->total() }} kost
                </div>
                <div>
                    {{ $kosts->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
