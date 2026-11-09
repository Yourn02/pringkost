@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="fw-bold mb-3">{{ $kost->name }}</h1>
            <p class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $kost->address }}</p>

            <span
                class="badge bg-{{ $kost->type == 'putra' ? 'primary' : ($kost->type == 'putri' ? 'danger' : 'success') }} text-capitalize mb-3">
                Kost {{ $kost->type }}
            </span>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h3 class="text-success fw-bold">Rp {{ number_format($kost->price, 0, ',', '.') }} <small
                            class="text-muted fs-6">/bulan</small></h3>
                    <hr>
                    <h5 class="fw-bold">Deskripsi Kost</h5>
                    <p>{{ $kost->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Kamar</h5>
                    <p class="mb-2">Total Kamar: <strong>{{ $kost->total_rooms }}</strong></p>
                    <p class="mb-4">Sisa Kamar: <strong class="text-success">{{ $kost->available_rooms }}</strong></p>

                    <!-- Form Pengajuan Sewa / Booking -->
                    <form action="{{ route('booking.store', $kost->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="start_date" class="form-label fw-bold">Tanggal Mulai Sewa</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="duration_months" class="form-label fw-bold">Durasi (Bulan)</label>
                            <input type="number" class="form-control" name="duration_months" value="1" min="1" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Ajukan Sewa Sekarang</button>
                    </form>

                    @auth
                    <form action="{{ route('wishlist.toggle', $kost->id) }}" method="POST" class="mb-3">
                        @csrf
                        @php
                        $isFavorited = \App\Models\Wishlist::where('user_id', auth()->id())->where('kost_id',
                        $kost->id)->exists();
                        @endphp
                        <button type="submit"
                            class="btn {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} w-100">
                            <i class="fa-solid fa-heart me-1"></i>
                            {{ $isFavorited ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection