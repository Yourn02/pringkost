@extends('layouts.user')

@section('title', 'Detail Kost: ' . $kost->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Top Action Bar -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('user.kosts.index') }}" class="btn btn-sm btn-light border rounded-pill px-2.5">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="h4 fw-bold text-dark mb-0">{{ $kost->name }}</h2>
                <span class="badge bg-light text-dark border text-capitalize px-2.5 py-1 rounded-pill small">
                    Kost {{ $kost->type }}
                </span>
            </div>
            <p class="text-muted small mb-0"><i class="fa-solid fa-location-dot me-1 text-secondary"></i>
                {{ $kost->address }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Form Favorit Toggle -->
            <form action="{{ route('user.favorites.toggle', $kost->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit"
                    class="btn btn-sm {{ $isFavorited ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3 fw-semibold">
                    <i class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-heart me-1"></i>
                    {{ $isFavorited ? 'Tersimpan di Favorit' : 'Tambah ke Favorit' }}
                </button>
            </form>
            <a href="#bookingCard" class="btn btn-burnt btn-sm rounded-pill px-3 fw-semibold shadow-xs">
                <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Sewa Sekarang
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Column: Photos & Details -->
        <div class="col-lg-8">
            <!-- Galeri Foto Kost -->
            <div class="card border border-light-subtle shadow-xs rounded-4 mb-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-regular fa-images me-2 text-muted"></i> Galeri Foto
                    Properti</h5>
                @if($kost->images->isNotEmpty())
                <div class="row g-2">
                    @foreach($kost->images as $img)
                    <div class="col-md-4 col-6">
                        <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                class="img-fluid rounded-3 object-fit-cover w-100 shadow-xs" style="height: 180px;"
                                alt="Foto Kost">
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5 text-muted bg-light rounded-3">
                    <i class="fa-solid fa-image fs-1 mb-2 opacity-50"></i>
                    <p class="small mb-0">Pemilik belum mengunggah foto tambahan.</p>
                </div>
                @endif
            </div>

            <!-- Deskripsi & Peraturan -->
            <div class="card border border-light-subtle shadow-xs rounded-4 mb-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">Deskripsi Lengkap Kost</h5>
                <p class="text-secondary" style="white-space: pre-line; line-height: 1.8;">{{ $kost->description }}</p>

                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-2">Alamat & Lokasi</h5>
                <p class="text-secondary mb-0"><i class="fa-solid fa-location-dot me-2 text-danger"></i>
                    {{ $kost->address }}</p>
            </div>

            <!-- Fasilitas Tersedia -->
            <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">Fasilitas yang Disediakan</h5>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($kost->facilities as $fac)
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium">
                        <i class="fa-solid fa-check text-success me-1.5"></i> {{ $fac->name }}
                    </span>
                    @empty
                    <span class="text-muted small">Tidak ada informasi fasilitas khusus yang dicantumkan.</span>
                    @endforelse
                </div>
            </div>
        </div>


        <!-- Lokasi Peta & Tombol Google Maps -->
        @if($kost->latitude && $kost->longitude)
        <div class="mt-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <h5 class="fw-extrabold text-dark mb-0">Lokasi Kost</h5>
                <a href="https://www.google.com/maps?q={{ $kost->latitude }},{{ $kost->longitude }}" target="_blank"
                    rel="noopener noreferrer" class="btn btn-sm text-white fw-semibold rounded-pill px-3"
                    style="background-color: #D97706;">
                    <i class="fa-solid fa-map-location-dot me-1"></i> Buka di Google Maps
                </a>
            </div>
            <p class="text-muted small mb-3">{{ $kost->address }}</p>
            <div id="kostDetailMap" data-lat="{{ $kost->latitude }}" data-lng="{{ $kost->longitude }}"
                data-name="{{ $kost->name }}"
                style="height: 300px; border-radius: 16px; border: 1px solid var(--pk-cream-dark); z-index: 1;"></div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapEl = document.getElementById('kostDetailMap');
            if (!mapEl) return;

            const lat = parseFloat(mapEl.dataset.lat);
            const lng = parseFloat(mapEl.dataset.lng);
            const kostName = mapEl.dataset.name || 'Lokasi Kost';

            if (Number.isNaN(lat) || Number.isNaN(lng)) return;

            const detailMap = L.map('kostDetailMap').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(detailMap);

            L.marker([lat, lng])
                .addTo(detailMap)
                .bindPopup(kostName)
                .openPopup();
        });
        </script>
        @else
        <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="text-muted small">Titik koordinat peta belum diatur oleh pemilik.</span>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($kost->address) }}" target="_blank"
                rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                Cari Alamat di Google Maps
            </a>
        </div>
        @endif

        <!-- Side Column: Booking Form & Info Pemilik -->
        <div class="col-lg-4">
            <!-- Booking Card Form -->
            <div id="bookingCard" class="card border border-light-subtle shadow-xs rounded-4 mb-4 bg-white p-4">
                <div class="mb-3">
                    <span class="text-muted small d-block">Tarif Sewa Bulanan</span>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($kost->price, 0, ',', '.') }}<span
                            class="fs-6 text-muted fw-normal">/bulan</span></h3>
                </div>

                <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-light border mb-3">
                    <span class="small text-muted">Ketersediaan Kamar:</span>
                    <span class="fw-bold text-dark small">{{ $kost->available_rooms }} dari {{ $kost->total_rooms }}
                        Kamar</span>
                </div>

                @if($kost->available_rooms > 0)
                <form action="{{ route('user.bookings.store', $kost->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Mulai Tanggal Sewa <span
                                class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control rounded-3" min="{{ date('Y-m-d') }}"
                            value="{{ old('start_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Durasi Sewa (Bulan) <span
                                class="text-danger">*</span></label>
                        <select name="duration_months" id="durationSelect" class="form-select rounded-3" required
                            onchange="calculateTotal({{ $kost->price }})">
                            @for($i = 1; $i <= 12; $i++) <option value="{{ $i }}"
                                {{ old('duration_months') == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                                @endfor
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light border mb-3">
                        <span class="small fw-semibold text-muted">Estimasi Total Biaya:</span>
                        <span id="totalDisplay" class="fw-bold text-dark">Rp
                            {{ number_format($kost->price, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-burnt w-100 py-2.5 fw-bold rounded-3 shadow-xs">
                        <i class="fa-solid fa-paper-plane me-1"></i> Ajukan Pemesanan
                    </button>
                </form>
                @else
                <div class="alert alert-secondary border-0 text-center py-3 mb-0 small">
                    <i class="fa-solid fa-door-closed fs-3 d-block mb-2 text-muted"></i>
                    Seluruh kamar pada properti ini sedang penuh.
                </div>
                @endif
            </div>

            <!-- Informasi Kontak Pemilik -->
            <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-4">
                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Kontak Pemilik Kost</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-light border fw-bold text-dark d-flex align-items-center justify-content-center"
                        style="width: 46px; height: 46px; font-size: 1.1rem;">
                        {{ strtoupper(substr($kost->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $kost->user->name ?? 'Pemilik Kost' }}</h6>
                        <span class="text-muted fs-xs">Mitra Terverifikasi</span>
                    </div>
                </div>

                @if(!empty($kost->user->phone))
                @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $kost->user->phone);
                if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
                }
                @endphp
                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($kost->user->name ?? '') }},%20saya%20tertarik%20dengan%20properti%20{{ urlencode($kost->name) }}%20di%20PringKost."
                    target="_blank" class="btn btn-outline-success w-100 rounded-pill btn-sm fw-semibold">
                    <i class="fa-brands fa-whatsapp me-1"></i> Tanya Pemilik via WhatsApp
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function calculateTotal(pricePerMonth) {
    const select = document.getElementById('durationSelect');
    const months = parseInt(select.value) || 1;
    const total = pricePerMonth * months;
    document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection