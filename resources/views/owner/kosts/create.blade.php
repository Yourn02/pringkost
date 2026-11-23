@extends('layouts.owner')

@section('title', 'Tambah Properti Kost Baru')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Tambah Properti Kost Baru</h4>
                        <span class="text-muted small">Lengkapi data properti Anda. Listing akan diverifikasi terlebih
                            dahulu oleh Administrator sebelum tampil di publik.</span>
                    </div>
                    <a href="{{ route('owner.kosts.index') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('owner.kosts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-dark">Nama Properti Kost <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                placeholder="Contoh: Kost Melati Asri Pringsewu" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Jenis Kost <span
                                    class="text-danger">*</span></label>
                            <select name="type" class="form-select rounded-3 @error('type') is-invalid @enderror"
                                required>
                                <option value="putra" {{ old('type') === 'putra' ? 'selected' : '' }}>Khusus Putra
                                </option>
                                <option value="putri" {{ old('type') === 'putri' ? 'selected' : '' }}>Khusus Putri
                                </option>
                                <option value="campur" {{ old('type') === 'campur' ? 'selected' : '' }}>Campur</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Tarif Sewa per Bulan (Rp) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="price"
                                class="form-control rounded-3 @error('price') is-invalid @enderror"
                                placeholder="Contoh: 600000" value="{{ old('price') }}" required min="0">
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Total Seluruh Kamar <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="total_rooms"
                                class="form-control rounded-3 @error('total_rooms') is-invalid @enderror"
                                placeholder="Contoh: 10" value="{{ old('total_rooms') }}" required min="1">
                            @error('total_rooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Kamar Kosong / Siap Huni <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="available_rooms"
                                class="form-control rounded-3 @error('available_rooms') is-invalid @enderror"
                                placeholder="Contoh: 3" value="{{ old('available_rooms') }}" required min="0">
                            @error('available_rooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Alamat Lengkap & Patokan Lokasi <span
                                class="text-danger">*</span></label>
                        <textarea name="address" class="form-control rounded-3 @error('address') is-invalid @enderror"
                            rows="2"
                            placeholder="Contoh: Jl. Ahmad Yani No. 12, Pringsewu Barat (Dekat Kampus UAP Pringsewu)"
                            required>{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Lokasi Peta / Koordinat -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titik Lokasi di Peta</label>
                        <p class="text-muted small mb-2">Klik pada peta untuk menentukan lokasi kost, atau geser pin ke
                            titik yang tepat.</p>

                        <div id="mapPicker" style="height: 320px; border-radius: 0.5rem; border: 1px solid #CBD5E1;"
                            class="mb-3"></div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label small text-muted">Latitude</label>
                                <input type="text" name="latitude" id="latitude"
                                    value="{{ old('latitude', '-5.3585') }}"
                                    class="form-control @error('latitude') is-invalid @enderror" readonly>
                                @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label small text-muted">Longitude</label>
                                <input type="text" name="longitude" id="longitude"
                                    value="{{ old('longitude', '104.9744') }}"
                                    class="form-control @error('longitude') is-invalid @enderror" readonly>
                                @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Deskripsi & Peraturan Kost <span
                                class="text-danger">*</span></label>
                        <textarea name="description"
                            class="form-control rounded-3 @error('description') is-invalid @enderror" rows="4"
                            placeholder="Jelaskan ukuran kamar, fasilitas listrik/air, jam malam, dan aturan kost lainnya..."
                            required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Fasilitas Kost Checkboxes -->
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label small fw-bold text-dark mb-2 d-block">Pilihan Fasilitas yang
                            Tersedia</label>
                        <div class="row g-2">
                            @forelse($facilities as $facility)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facilities[]"
                                        value="{{ $facility->id }}" id="fac_{{ $facility->id }}"
                                        {{ is_array(old('facilities')) && in_array($facility->id, old('facilities')) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="fac_{{ $facility->id }}">
                                        {{ $facility->name }}
                                    </label>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-muted small">Belum ada master fasilitas terdaftar.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Upload Foto Multi -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Unggah Foto Properti Kost (Maksimal 5
                            Foto)</label>
                        <input type="file" name="images[]"
                            class="form-control rounded-3 @error('images') is-invalid @enderror" multiple
                            accept="image/*">
                        <div class="form-text fs-xs">Format: JPG, JPEG, PNG, WEBP. Maksimal ukuran 2MB per gambar.</div>
                        @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('owner.kosts.index') }}" class="btn btn-light border px-4 rounded-3">Batal</a>
                        <button type="submit" class="btn btn-burnt px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan & Ajukan Listing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultLat = parseFloat(document.getElementById('latitude').value) || -5.3585;
    const defaultLng = parseFloat(document.getElementById('longitude').value) || 104.9744;

    const map = L.map('mapPicker').setView([defaultLat, defaultLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

    function updateCoords(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);
    }

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });
});
</script>
@endsection