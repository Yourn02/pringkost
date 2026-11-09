@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Hero Section -->
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border text-center">
        <h1 class="display-5 fw-bold text-dark">Cari Kost Idaman di Pringsewu</h1>
        <p class="col-md-8 mx-auto fs-4 text-muted">Mudah, cepat, dan langsung terhubung dengan pemilik kost via
            WhatsApp[cite: 1].</p>

        <!-- Form Pencarian & Filter -->
        <form action="{{ route('home') }}" method="GET" class="row g-3 justify-content-center mt-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau lokasi kost..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Semua Jenis Kost</option>
                    <option value="putra" {{ request('type') == 'putra' ? 'selected' : '' }}>Putra</option>
                    <option value="putri" {{ request('type') == 'putri' ? 'selected' : '' }}>Putri</option>
                    <option value="campur" {{ request('type') == 'campur' ? 'selected' : '' }}>Campur</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100"><i class="fa-solid fa-search me-1"></i> Cari</button>
            </div>
        </form>
    </div>

    <!-- Daftar Kost -->
    <h2 class="mb-4 fw-bold">Daftar Kost Tersedia</h2>
    <div class="row">
        @forelse($kosts as $kost)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- Foto Utama Kost -->
                @if($kost->images->count() > 0)
                <img src="{{ asset('storage/' . $kost->images->first()->image_path) }}" class="card-img-top"
                    alt="{{ $kost->name }}" style="height: 200px; object-fit: cover;">
                @else
                <img src="https://via.placeholder.com/400x200?text=PringKost" class="card-img-top" alt="Default Image"
                    style="height: 200px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <span
                        class="badge bg-{{ $kost->type == 'putra' ? 'primary' : ($kost->type == 'putri' ? 'danger' : 'success') }} mb-2 text-capitalize">
                        Kost {{ $kost->type }}
                    </span>
                    <h5 class="card-title fw-bold">{{ $kost->name }}</h5>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i> {{ $kost->address }}
                    </p>
                    <h4 class="text-success fw-bold">Rp {{ number_format($kost->price, 0, ',', '.') }} <small
                            class="text-muted fs-6">/bulan</small></h4>
                    <p class="badge bg-info text-dark mb-3">Sisa Kamar: {{ $kost->available_rooms }}</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="{{ route('kost.show', $kost->id) }}" class="btn btn-outline-dark btn-sm w-100 mb-2">Lihat
                        Detail</a>
                    <!-- Tombol WhatsApp Click-to-Chat -->
                    <a href="https://wa.me/{{ $kost->user->phone ?? '628123456789' }}?text=Halo,%20saya%20tertarik%20dengan%20kost%20{{ urlencode($kost->name) }}"
                        target="_blank" class="btn btn-success btn-sm w-100">
                        <i class="fa-brands fa-whatsapp me-1"></i> Hubungi Pemilik
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted fs-5">Belum ada data kost yang disetujui atau sesuai pencarian.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $kosts->links() }}
    </div>
</div>
@endsection