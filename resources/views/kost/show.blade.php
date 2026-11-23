@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb / Back Link -->
    <div class="mb-4">
        <a href="{{ route('kost.index') }}" class="text-decoration-none text-muted small fw-semibold">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Jelajah Kost
        </a>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Galeri Foto, Kelola Foto Owner, Detail Info, Fasilitas, Peta -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 mb-4"
                style="background-color: #FFFFFF; border-radius: 24px; border: 1px solid var(--pk-cream-dark);">
                <!-- Header Title & Badges -->
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <span class="badge-editorial badge-type-{{ $kost->type }} text-capitalize mb-2 d-inline-block">
                            Kost {{ $kost->type }}
                        </span>
                        <h1 class="fw-extrabold text-dark mb-1" style="font-size: 2rem; letter-spacing: -0.5px;">
                            {{ $kost->name }}
                        </h1>
                        <p class="text-muted mb-0">
                            <i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $kost->address }}
                        </p>
                    </div>

                    @auth
                    @php
                    $isFavorited = \App\Models\Wishlist::where('user_id', auth()->id())
                    ->where('kost_id', $kost->id)
                    ->exists();
                    @endphp
                    <form action="{{ route('wishlist.toggle', $kost->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm px-3 py-2 rounded-pill fw-semibold border"
                            style="{{ $isFavorited ? 'background-color: #FEE2E2; color: #DC2626; border-color: #FCA5A5;' : 'background-color: var(--pk-cream-light); color: var(--pk-black-muted); border-color: var(--pk-cream-dark);' }}">
                            <i class="{{ $isFavorited ? 'fa-solid' : 'fa-regular' }} fa-heart me-1"></i>
                            {{ $isFavorited ? 'Tersimpan' : 'Simpan' }}
                        </button>
                    </form>
                    @endauth
                </div>

                <!-- Galeri Foto / Cover Image -->
                <div class="mb-4">
                    @if($kost->images->isNotEmpty())
                    <div id="kostCarousel" class="carousel slide rounded-4 overflow-hidden position-relative"
                        data-bs-ride="false" style="background-color: var(--pk-cream-light);">
                        <div class="carousel-inner">
                            @foreach($kost->images as $index => $img)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="d-block w-100 object-fit-cover" style="height: 400px;"
                                    alt="Foto {{ $kost->name }} ke-{{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>

                        @if($kost->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#kostCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#kostCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                        @endif
                    </div>

                    <!-- Thumbnail Semua Foto Detail -->
                    @if($kost->images->count() > 1)
                    <div class="d-flex flex-wrap gap-2 mt-3" id="kostThumbnailList">
                        @foreach($kost->images as $index => $img)
                        <button type="button" data-bs-target="#kostCarousel" data-bs-slide-to="{{ $index }}"
                            class="p-0 border rounded-3 overflow-hidden {{ $index === 0 ? 'active border-2' : 'opacity-75' }}"
                            style="width: 86px; height: 64px; {{ $index === 0 ? 'border-color: var(--pk-burnt) !important;' : 'border-color: var(--pk-cream-dark) !important;' }}"
                            aria-label="Lihat foto {{ $index + 1 }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                class="w-100 h-100 object-fit-cover d-block" alt="Thumbnail {{ $index + 1 }}">
                        </button>
                        @endforeach
                    </div>
                    @endif
                    @else
                    @php
                    $stockGallery = [
                    asset('images/kost-1.jpg'),
                    asset('images/kost-2.jpg'),
                    asset('images/kost-3.jpg'),
                    ];
                    $countStock = count($stockGallery);
                    $startIdx = ($kost->id ?? 0) % $countStock;
                    $orderedGallery = [];
                    for ($i = 0; $i < $countStock; $i++) { $orderedGallery[]=$stockGallery[($startIdx + $i) %
                        $countStock]; } @endphp <div id="kostStockCarousel"
                        class="carousel slide rounded-4 overflow-hidden position-relative" data-bs-ride="false"
                        style="background-color: var(--pk-cream-light);">
                        <div class="carousel-inner">
                            @foreach($orderedGallery as $index => $stockImg)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $stockImg }}" class="d-block w-100 object-fit-cover" style="height: 400px;"
                                    alt="Foto Stok {{ $kost->name }} ke-{{ $index + 1 }}">
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#kostStockCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#kostStockCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach($orderedGallery as $index => $stockImg)
                    <button type="button" data-bs-target="#kostStockCarousel" data-bs-slide-to="{{ $index }}"
                        class="p-0 border rounded-3 overflow-hidden {{ $index === 0 ? 'active border-2' : 'opacity-75' }}"
                        style="width: 86px; height: 64px; {{ $index === 0 ? 'border-color: var(--pk-burnt) !important;' : 'border-color: var(--pk-cream-dark) !important;' }}"
                        aria-label="Lihat foto stok {{ $index + 1 }}">
                        <img src="{{ $stockImg }}" class="w-100 h-100 object-fit-cover d-block"
                            alt="Thumbnail stok {{ $index + 1 }}">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Panel Kelola Foto Katalog (Khusus Pemilik Kost) -->
            @if(auth()->check() && auth()->id() === $kost->user_id)
            <div class="p-3 mb-4 rounded-4"
                style="background-color: var(--pk-cream-light); border: 1px dashed var(--pk-cream-dark);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold small text-dark">
                        <i class="fa-solid fa-images me-1 text-warning"></i> Kelola Foto Katalog (Mode Pemilik)
                    </span>
                </div>

                @if($kost->images->isNotEmpty())
                <div class="row g-2 mb-3">
                    @foreach($kost->images as $img)
                    <div class="col-4 col-sm-3">
                        <div class="position-relative rounded-3 overflow-hidden border bg-white" style="height: 85px;">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-100 h-100 object-fit-cover"
                                alt="Thumbnail">
                            <form action="{{ route('owner.kosts.images.destroy', $img->id) }}" method="POST"
                                onsubmit="return confirm('Hapus foto ini dari katalog?');"
                                class="position-absolute top-0 end-0 m-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-danger btn-sm p-0 d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 24px; height: 24px; border-radius: 50%;" title="Hapus Foto">
                                    <i class="fa-solid fa-trash-can" style="font-size: 0.7rem;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="small text-muted mb-2">
                    Saat ini masih memakai foto stok bawaan. Unggah foto asli kost Anda di bawah ini:
                </p>
                @endif

                <form action="{{ route('owner.kosts.images.store', $kost->id) }}" method="POST"
                    enctype="multipart/form-data" class="d-flex flex-wrap gap-2">
                    @csrf
                    <input type="file" name="images[]" class="form-control form-control-sm flex-grow-1" accept="image/*"
                        multiple required>
                    <button type="submit" class="btn btn-sm btn-burnt fw-semibold px-3">
                        <i class="fa-solid fa-upload me-1"></i> Upload Foto
                    </button>
                </form>
            </div>
            @endif

            <!-- Fasilitas Kost -->
            <div class="mb-4">
                <h5 class="fw-extrabold text-dark mb-3">Fasilitas yang Disediakan</h5>
                @if($kost->facilities->isNotEmpty())
                <div class="row g-2">
                    @foreach($kost->facilities as $fac)
                    <div class="col-sm-6 col-md-4">
                        <div class="p-2 rounded-3 d-flex align-items-center gap-2"
                            style="background-color: var(--pk-cream-light); border: 1px solid var(--pk-cream-dark);">
                            <i class="fa-solid fa-circle-check text-success small"></i>
                            <span class="small fw-semibold text-dark">{{ $fac->name }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted small">Tidak ada keterangan fasilitas tambahan.</p>
                @endif
            </div>

            <hr style="border-color: var(--pk-cream-dark);">

            <!-- Deskripsi Lengkap -->
            <div class="mb-4">
                <h5 class="fw-extrabold text-dark mb-3">Deskripsi & Peraturan Kost</h5>
                <p class="text-secondary" style="line-height: 1.8;">
                    {!! nl2br(e($kost->description ?? 'Pemilik belum menambahkan deskripsi untuk properti kost ini.'))
                    !!}
                </p>
            </div>

            <hr style="border-color: var(--pk-cream-dark);">

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
                    style="height: 300px; border-radius: 16px; border: 1px solid var(--pk-cream-dark); z-index: 1;">
                </div>
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
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($kost->address) }}"
                    target="_blank" rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    Cari Alamat di Google Maps
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Kolom Kanan: Card Aksi, Kontak WhatsApp & Form Pengajuan -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 sticky-top"
            style="top: 88px; z-index: 10; background-color: #FFFFFF; border-radius: 24px; border: 1px solid var(--pk-cream-dark);">
            <!-- Harga -->
            <div class="mb-4">
                <span class="text-muted small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Biaya Sewa</span>
                <div class="d-flex align-items-baseline gap-1 mt-1">
                    <span class="display-6 fw-extrabold" style="color: var(--pk-burnt);">
                        Rp {{ number_format($kost->price, 0, ',', '.') }}
                    </span>
                    <span class="text-muted fw-semibold">/ bulan</span>
                </div>
            </div>

            <!-- Info Ketersediaan Kamar -->
            <div class="p-3 rounded-4 mb-4"
                style="background-color: var(--pk-cream-light); border: 1px solid var(--pk-cream-dark);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small text-muted">Total Kamar:</span>
                    <span class="fw-bold text-dark">{{ $kost->total_rooms }} Kamar</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Kamar Tersedia:</span>
                    <span
                        class="badge px-3 py-1 rounded-pill {{ $kost->available_rooms > 0 ? 'bg-success text-white' : 'bg-danger text-white' }} fw-bold">
                        {{ $kost->available_rooms > 0 ? $kost->available_rooms . ' Kamar Tersedia' : 'Penuh' }}
                    </span>
                </div>
            </div>

            <!-- Tombol WhatsApp Direct Action -->
            @php
            $ownerPhone = $kost->user->phone ?? '';
            $cleanPhone = preg_replace('/[^0-9]/', '', $ownerPhone);
            if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
            }
            $waText = urlencode("Halo, saya melihat kost *{$kost->name}* di PringKost. Apakah kamar masih tersedia?");
            @endphp

            @if($cleanPhone)
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" target="_blank"
                class="btn-whatsapp w-100 mb-3 text-decoration-none">
                <i class="fa-brands fa-whatsapp fs-5"></i>
                <span>Tanya Pemilik via WhatsApp</span>
            </a>
            @else
            <div class="alert alert-secondary py-2 small text-center mb-3">
                Kontak WhatsApp pemilik belum tercantum.
            </div>
            @endif

            @php
            $isAdmin = auth()->check() && auth()->user()->role === 'admin';
            @endphp

            @unless($isAdmin)
            <!-- Ajukan Sewa Form + Kalkulator Biaya -->
            <div class="pt-3 border-top" style="border-color: var(--pk-cream-dark) !important;">
                <h6 class="fw-bold text-dark mb-3">Ajukan Booking / Reservasi</h6>

                @if($kost->available_rooms > 0)
                <form action="{{ route('booking.store', $kost->id) }}" method="POST" id="bookingForm"
                    data-price="{{ $kost->price }}">
                    @csrf
                    <div class="mb-3">
                        <label for="start_date" class="form-label small fw-bold text-muted">Mulai Tanggal</label>
                        <input type="date" class="form-control form-control-editorial" id="start_date" name="start_date"
                            min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="duration_months" class="form-label small fw-bold text-muted">Durasi Sewa
                            (Bulan)</label>
                        <input type="number" class="form-control form-control-editorial" id="duration_months"
                            name="duration_months" value="{{ old('duration_months', 1) }}" min="1" max="24" required>
                    </div>

                    <!-- Box Ringkasan Kalkulator Sewa -->
                    <div class="p-3 rounded-4 mb-3" style="background-color: #F8FAFC; border: 1px dashed #CBD5E1;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-muted">Tarif / Bulan:</span>
                            <span class="small fw-semibold text-dark">Rp
                                {{ number_format($kost->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-muted">Durasi:</span>
                            <span class="small fw-semibold text-dark"><span id="calcDuration">1</span> Bulan</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small text-muted">Perkiraan Selesai:</span>
                            <span class="small fw-semibold text-dark" id="calcEndDate">-</span>
                        </div>
                        <hr class="my-2" style="border-color: #CBD5E1;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small fw-bold text-dark">Estimasi Total:</span>
                            <span class="fw-extrabold fs-5" style="color: #D97706;" id="calcTotalPrice">
                                Rp {{ number_format($kost->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-burnt w-100 fw-semibold">
                        <i class="fa-solid fa-calendar-check me-1"></i> Ajukan Sewa Kamar
                    </button>
                </form>
                @else
                <div class="alert alert-danger py-2 small text-center mb-0 rounded-3">
                    Maaf, seluruh kamar di kost ini sedang penuh.
                </div>
                @endif
            </div>
            @endunless
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    if (!form) return;

    const monthlyPrice = parseFloat(form.dataset.price) || 0;
    const startDateInput = document.getElementById('start_date');
    const durationInput = document.getElementById('duration_months');

    const calcDurationEl = document.getElementById('calcDuration');
    const calcEndDateEl = document.getElementById('calcEndDate');
    const calcTotalPriceEl = document.getElementById('calcTotalPrice');

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function formatTanggalIndo(dateObj) {
        return dateObj.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    function updateCalculator() {
        let months = parseInt(durationInput.value, 10);
        if (Number.isNaN(months) || months < 1) {
            months = 1;
        }

        const total = monthlyPrice * months;
        calcDurationEl.textContent = months;
        calcTotalPriceEl.textContent = formatRupiah(total);

        if (startDateInput.value) {
            const parts = startDateInput.value.split('-');
            const start = new Date(parts[0], parts[1] - 1, parts[2]);
            const end = new Date(start);
            end.setMonth(end.getMonth() + months);
            calcEndDateEl.textContent = formatTanggalIndo(end);
        } else {
            calcEndDateEl.textContent = '-';
        }
    }

    startDateInput.addEventListener('change', updateCalculator);
    durationInput.addEventListener('input', updateCalculator);
    updateCalculator();

    const carouselEl = document.getElementById('kostCarousel');
    const thumbButtons = document.querySelectorAll('#kostThumbnailList button');
    if (carouselEl && thumbButtons.length > 0) {
        carouselEl.addEventListener('slid.bs.carousel', function(event) {
            thumbButtons.forEach((btn, idx) => {
                const isActive = idx === event.to;
                btn.classList.toggle('active', isActive);
                btn.classList.toggle('border-2', isActive);
                btn.classList.toggle('opacity-75', !isActive);
                btn.style.borderColor = isActive ? 'var(--pk-burnt)' : 'var(--pk-cream-dark)';
            });
        });
    }
});
</script>
@endsection