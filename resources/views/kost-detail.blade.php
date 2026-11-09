@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-dark">Beranda</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $kost->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Kolom Foto & Detail -->
        <div class="col-md-8">
            <h2 class="fw-bold mb-2">{{ $kost->name }}</h2>
            <p class="text-muted mb-3"><i class="fa-solid fa-location-dot me-1"></i> {{ $kost->address }}</p>

            <!-- Galeri Foto -->
            <div class="mb-4">
                @if($kost->images->count() > 0)
                <div id="kostCarousel" class="carousel slide shadow-sm rounded overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($kost->images as $index => $image)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100"
                                style="height: 400px; object-fit: cover;" alt="Foto Kost">
                        </div>
                        @endforeach
                    </div>
                    @if($kost->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#kostCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#kostCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                    @endif
                </div>
                @else
                <img src="https://via.placeholder.com/800x400?text=PringKost" class="img-fluid rounded shadow-sm"
                    alt="Default">
                @endif
            </div>

            <!-- Deskripsi -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Deskripsi & Peraturan</h5>
                    <p style="white-space: pre-line;">{{ $kost->description }}</p>
                </div>
            </div>

            <!-- Fasilitas -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Fasilitas Kost</h5>
                    <div class="row">
                        @forelse($kost->facilities as $facility)
                        <div class="col-md-4 mb-2">
                            <i class="fa-solid fa-check text-success me-2"></i> {{ $facility->name }}
                        </div>
                        @empty
                        <p class="text-muted">Tidak ada fasilitas khusus yang tercantum.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Harga & Hubungi Pemilik -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <span
                        class="badge bg-{{ $kost->type == 'putra' ? 'primary' : ($kost->type == 'putri' ? 'danger' : 'success') }} mb-2 text-capitalize">
                        Kost {{ $kost->type }}
                    </span>
                    <h3 class="text-success fw-bold mb-3">Rp {{ number_format($kost->price, 0, ',', '.') }} <small
                            class="text-muted fs-6">/bulan</small></h3>

                    <hr>

                    <p class="mb-2"><strong>Sisa Kamar:</strong> <span
                            class="badge bg-info text-dark">{{ $kost->available_rooms }} dari {{ $kost->total_rooms }}
                            kamar</span></p>
                    <p class="mb-4"><strong>Pengelola:</strong> {{ $kost->user->name ?? 'Pemilik Kost' }}</p>

                    <a href="https://wa.me/{{ $kost->user->phone ?? '628123456789' }}?text=Halo,%20saya%20tertarik%20dengan%20kost%20{{ urlencode($kost->name) }}"
                        target="_blank" class="btn btn-success w-100 py-2 fw-bold">
                        <i class="fa-brands fa-whatsapp me-2 fs-5"></i> Hubungi Pemilik via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection