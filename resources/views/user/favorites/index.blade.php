@extends('layouts.user')

@section('title', 'Kost Favorit Saya')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Kost Favorit Saya</h2>
            <p class="text-muted small mb-0">Daftar kamar kost yang Anda tandai dan simpan untuk referensi sewa.</p>
        </div>
        <div>
            <a href="{{ route('user.kosts.index') }}" class="btn btn-burnt rounded-pill fw-semibold btn-sm px-3 shadow-xs">
                <i class="fa-solid fa-magnifying-glass me-1"></i> Cari Kost Lainnya
            </a>
        </div>
    </div>

    <!-- Grid Kost Favorit -->
    <div class="row g-3 mb-4">
        @forelse($wishlists as $item)
            @php $kost = $item->kost; @endphp
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border border-light-subtle rounded-4 overflow-hidden shadow-xs hover-shadow transition-all bg-white">
                    <div class="position-relative" style="height: 180px; background-color: #f3f4f6;">
                        @if($kost && $kost->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $kost->images->first()->image_path) }}" alt="{{ $kost->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                <i class="fa-solid fa-image fs-1 opacity-50"></i>
                            </div>
                        @endif

                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-white text-dark border text-capitalize small shadow-xs">
                                {{ $kost->type ?? 'Kost' }}
                            </span>
                        </div>

                        <!-- Tombol Hapus Favorit -->
                        <form action="{{ route('user.favorites.destroy', $kost->id) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-white rounded-circle p-2 shadow-xs border-0 bg-white" title="Hapus dari Favorit" onclick="return confirm('Hapus kost ini dari favorit Anda?')">
                                <i class="fa-solid fa-heart text-danger"></i>
                            </button>
                        </form>
                    </div>

                    <div class="card-body p-3.5 d-flex flex-column">
                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $kost->name }}">
                            <a href="{{ route('user.kosts.show', $kost->id) }}" class="text-dark text-decoration-none hover-underline">
                                {{ $kost->name }}
                            </a>
                        </h6>
                        <p class="text-muted fs-xs text-truncate mb-2" title="{{ $kost->address }}">
                            <i class="fa-solid fa-location-dot me-1 text-secondary"></i> {{ $kost->address }}
                        </p>

                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                            <div>
                                <span class="text-muted fs-xs d-block">Tarif per Bulan</span>
                                <h6 class="fw-bold text-dark mb-0">Rp {{ number_format($kost->price, 0, ',', '.') }}</h6>
                            </div>
                            <div class="d-flex gap-1.5">
                                <a href="{{ route('user.kosts.show', $kost->id) }}" class="btn btn-sm btn-burnt rounded-pill px-3 fs-xs fw-semibold">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="py-5 text-muted bg-white rounded-4 border border-light-subtle">
                    <i class="fa-solid fa-heart-crack fs-1 mb-2 opacity-50 text-secondary"></i>
                    <h5 class="fw-bold text-dark">Belum Ada Kost Favorit</h5>
                    <p class="small text-muted mb-3">Tandai kost yang kamu sukai di katalog pencarian agar mudah ditemukan kembali.</p>
                    <a href="{{ route('user.kosts.index') }}" class="btn btn-burnt rounded-pill btn-sm px-4">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Eksplorasi Kost Sekarang
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($wishlists->hasPages())
        <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan {{ $wishlists->firstItem() }} - {{ $wishlists->lastItem() }} dari {{ $wishlists->total() }} kost favorit
                </div>
                <div>
                    {{ $wishlists->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
