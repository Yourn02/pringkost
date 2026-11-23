@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Hero / Header Section -->
    <div class="row mb-5 align-items-center">
        <div class="col-lg-8">
            <span class="badge px-3 py-2 rounded-pill fw-bold mb-3"
                style="background-color: var(--pk-burnt-light); color: var(--pk-burnt); font-size: 0.85rem;">
                <i class="fa-solid fa-graduation-cap me-1"></i> Area Pringsewu
            </span>
            <h1 class="display-5 fw-extrabold text-dark mb-2" style="letter-spacing: -1px;">
                Temukan Kost Nyaman di <span style="color: var(--pk-burnt);">Pringsewu</span>
            </h1>
            <p class="lead text-muted mb-0" style="font-size: 1.1rem;">
                Informasi ketersediaan kamar, harga terjangkau, dan kontak langsung ke pemilik kost tanpa perantara.
            </p>
        </div>
    </div>

    <!-- Filter & Pencarian Editorial Box -->
    <div class="card border-0 shadow-sm p-4 mb-5"
        style="background-color: #FFFFFF; border-radius: 24px; border: 1px solid var(--pk-cream-dark);">
        <form action="{{ route('kost.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <!-- Input Keyword -->
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1"
                        style="letter-spacing: 0.5px;">Cari Kost / Lokasi</label>
                    <div class="input-group">
                        <span class="input-group-text border-0"
                            style="background-color: var(--pk-cream-light); border-top-left-radius: 14px; border-bottom-left-radius: 14px; border: 1.5px solid var(--pk-cream-dark); border-right: none;">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control form-control-editorial border-start-0"
                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                            value="{{ request('search') }}" placeholder="Nama kost atau jalan...">
                    </div>
                </div>

                <!-- Tipe Kost -->
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1"
                        style="letter-spacing: 0.5px;">Jenis Kost</label>
                    <select name="type" class="form-select form-select-editorial">
                        <option value="">Semua Tipe</option>
                        <option value="putra" {{ request('type') == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ request('type') == 'putri' ? 'selected' : '' }}>Putri</option>
                        <option value="campur" {{ request('type') == 'campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>

                <!-- Min Price -->
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1"
                        style="letter-spacing: 0.5px;">Min Harga (Rp)</label>
                    <input type="number" name="min_price" class="form-control form-control-editorial"
                        value="{{ request('min_price') }}" placeholder="0">
                </div>

                <!-- Max Price -->
                <div class="col-lg-2 col-md-6 col-6">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1"
                        style="letter-spacing: 0.5px;">Max Harga (Rp)</label>
                    <input type="number" name="max_price" class="form-control form-control-editorial"
                        value="{{ request('max_price') }}" placeholder="Maksimal">
                </div>

                <!-- Sorting -->
                <div class="col-lg-2 col-md-6 col-6">
                    <label class="form-label fw-bold small text-muted text-uppercase mb-1"
                        style="letter-spacing: 0.5px;">Urutan</label>
                    <select name="sort" class="form-select form-select-editorial">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Termurah
                        </option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Termahal
                        </option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top"
                style="border-color: var(--pk-cream-dark) !important;">
                <span class="small text-muted">
                    Menampilkan total listing yang telah diverifikasi
                </span>
                <div class="d-flex gap-2">
                    <a href="{{ route('kost.index') }}" class="btn btn-neutral btn-sm px-3">
                        Reset
                    </a>
                    <button type="submit" class="btn btn-burnt btn-sm px-4">
                        <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Grid Kartu Properti Kost -->
    <div class="row g-4">
        @if($kosts->isEmpty())
        <div class="col-12">
            <div class="card border-0 text-center py-5 shadow-sm"
                style="background-color: #FFFFFF; border-radius: 24px;">
                <div class="py-4">
                    <i class="fa-regular fa-folder-open display-4 text-muted mb-3"></i>
                    <h4 class="fw-bold text-dark">Tidak Ada Kost yang Sesuai</h4>
                    <p class="text-muted">Coba ubah kata kunci atau rentang harga pada filter di atas.</p>
                    <a href="{{ route('kost.index') }}" class="btn btn-burnt btn-sm px-4">Lihat Semua Kost</a>
                </div>
            </div>
        </div>
        @else
        @foreach($kosts as $kost)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm overflow-hidden"
                style="background-color: #FFFFFF; border-radius: 20px; border: 1px solid var(--pk-cream-dark);">

                @php
                $fallbackImages = [
                asset('images/kost-1.jpg'),
                asset('images/kost-2.jpg'),
                asset('images/kost-3.jpg'),
                ];
                $defaultCover = $fallbackImages[($kost->id ?? 0) % count($fallbackImages)];

                $firstImage = $kost->images->first() ?? null;
                $coverImage = ($firstImage && file_exists(public_path('storage/' . $firstImage->image_path)))
                ? asset('storage/' . $firstImage->image_path)
                : $defaultCover;
                @endphp

                <!-- Wrapper Cover Image + Badge -->
                <div class="position-relative overflow-hidden"
                    style="height: 210px; background-color: var(--pk-cream-light);">
                    <img src="{{ $coverImage }}" class="w-100 h-100 object-fit-cover d-block" alt="{{ $kost->name }}">

                    <!-- Badge Jenis Kost -->
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge-editorial badge-type-{{ $kost->type }} text-capitalize shadow-sm">
                            Kost {{ $kost->type }}
                        </span>
                    </div>

                    <!-- Badge Ketersediaan Kamar -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($kost->available_rooms > 0)
                        <span class="badge-editorial bg-white text-dark shadow-sm">
                            <span class="text-success fw-bold">●</span> Sisa {{ $kost->available_rooms }} Kamar
                        </span>
                        @else
                        <span class="badge-editorial bg-danger text-white shadow-sm">
                            Penuh
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Body Card -->
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-extrabold text-dark mb-1 text-truncate" title="{{ $kost->name }}">
                            {{ $kost->name }}
                        </h5>
                        <p class="text-muted small mb-3 text-truncate">
                            <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $kost->address }}
                        </p>

                        <!-- Fasilitas Tags -->
                        @if($kost->facilities->isNotEmpty())
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach($kost->facilities->take(3) as $fac)
                            <span class="badge px-2 py-1 rounded-pill small"
                                style="background-color: var(--pk-cream-light); color: var(--pk-black-muted); font-size: 0.72rem; border: 1px solid var(--pk-cream-dark);">
                                {{ $fac->name }}
                            </span>
                            @endforeach
                            @if($kost->facilities->count() > 3)
                            <span class="badge px-2 py-1 rounded-pill small"
                                style="background-color: var(--pk-cream-light); color: var(--pk-black-muted); font-size: 0.72rem;">
                                +{{ $kost->facilities->count() - 3 }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Price & CTA -->
                    <div class="pt-3 border-top d-flex align-items-center justify-content-between"
                        style="border-color: var(--pk-cream-dark) !important;">
                        <div>
                            <span class="text-muted small d-block" style="font-size: 0.75rem;">Mulai dari</span>
                            <span class="fw-extrabold fs-5" style="color: var(--pk-burnt);">
                                Rp {{ number_format($kost->price, 0, ',', '.') }}
                            </span>
                            <span class="text-muted small">/bln</span>
                        </div>
                        <a href="{{ route('kost.show', $kost->id) }}" class="btn btn-burnt btn-sm px-3">
                            Detail Kost
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $kosts->links() }}
    </div>
</div>
@endsection