@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Cari Kost Idaman Anda</h2>
            <p class="text-muted">Temukan pilihan kost terbaik dengan fasilitas lengkap di Pringsewu.</p>
        </div>
    </div>

    <!-- Form Pencarian & Filter -->
    <div class="card shadow-sm border-0 mb-4 p-3 bg-light">
        <form action="{{ route('kost.index') }}" method="GET">
            <div class="row g-3">
                <!-- Input Keyword -->
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Kata Kunci / Lokasi</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Cth: Nama kost atau kecamatan...">
                </div>

                <!-- Filter Tipe Kost -->
                <div class="col-md-2">
                    <label class="form-label fw-bold small">Tipe Kost</label>
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="putra" {{ request('type') == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ request('type') == 'putri' ? 'selected' : '' }}>Putri</option>
                        <option value="campur" {{ request('type') == 'campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>

                <!-- Filter Harga Minimum -->
                <div class="col-md-2">
                    <label class="form-label fw-bold small">Min Harga (Rp)</label>
                    <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}"
                        placeholder="0">
                </div>

                <!-- Filter Harga Maksimum -->
                <div class="col-md-2">
                    <label class="form-label fw-bold small">Max Harga (Rp)</label>
                    <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}"
                        placeholder="10000000">
                </div>

                <!-- Sorting -->
                <div class="col-md-2">
                    <label class="form-label fw-bold small">Urutkan</label>
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Termurah
                        </option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga
                            Termahal</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <a href="{{ route('kost.index') }}" class="btn btn-secondary btn-sm me-2">Reset Filter</a>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Cari Kost</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Daftar Kartu Kost -->
    <div class="row">
        @if($kosts->isEmpty())
        <div class="col-md-12">
            <div class="alert alert-warning text-center py-4">
                <h5 class="mb-1">Kost Tidak Ditemukan</h5>
                <p class="text-muted mb-0">Coba ubah kata kunci atau kriteria filter pencarian Anda.</p>
            </div>
        </div>
        @else
        @foreach($kosts as $kost)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- Placeholder Gambar jika belum ada multi-foto -->
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                    style="height: 200px;">
                    <span>Foto Kost</span>
                </div>
                <div class="card-body">
                    <span
                        class="badge bg-{{ $kost->type == 'putra' ? 'primary' : ($kost->type == 'putri' ? 'danger' : 'success') }} text-capitalize mb-2">
                        Kost {{ $kost->type }}
                    </span>
                    <h5 class="card-title fw-bold text-dark">{{ $kost->name }}</h5>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i> {{ $kost->address }}
                    </p>
                    <h6 class="text-success fw-bold">Rp {{ number_format($kost->price, 0, ',', '.') }} <small
                            class="text-muted fw-normal">/bulan</small></h6>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="{{ route('kost.show', $kost->id) }}" class="btn btn-outline-primary w-100 btn-sm">Lihat
                        Detail</a>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $kosts->links() }}
    </div>
</div>
@endsection