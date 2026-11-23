@extends('layouts.owner')

@section('title', 'Edit Properti: ' . $kost->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="fw-bold text-dark mb-0">Edit Properti Kost</h4>
                            <x-admin.status-badge :status="$kost->status" />
                        </div>
                        <span class="text-muted small">Perbarui data properti kost Anda.</span>
                    </div>
                    <a href="{{ route('owner.kosts.index') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                @if($kost->status === 'rejected' && $kost->rejection_reason)
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                    <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Pengajuan Sebelumnya
                        Ditolak Admin</div>
                    <div class="small">Catatan Evaluasi: {{ $kost->rejection_reason }}</div>
                    <div class="fs-xs text-muted mt-1">Setelah disimpan kembali, status properti otomatis berubah
                        menjadi <strong>Pending</strong> untuk diverifikasi ulang oleh admin.</div>
                </div>
                @endif

                <form id="editKostForm" action="{{ route('owner.kosts.update', $kost->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-dark">Nama Properti Kost <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control rounded-3 @error('name') is-invalid @enderror"
                                value="{{ old('name', $kost->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Jenis Kost <span
                                    class="text-danger">*</span></label>
                            <select name="type" class="form-select rounded-3 @error('type') is-invalid @enderror"
                                required>
                                <option value="putra" {{ old('type', $kost->type) === 'putra' ? 'selected' : '' }}>
                                    Khusus Putra</option>
                                <option value="putri" {{ old('type', $kost->type) === 'putri' ? 'selected' : '' }}>
                                    Khusus Putri</option>
                                <option value="campur" {{ old('type', $kost->type) === 'campur' ? 'selected' : '' }}>
                                    Campur</option>
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
                                value="{{ old('price', (int)$kost->price) }}" required min="0">
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Total Seluruh Kamar <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="total_rooms"
                                class="form-control rounded-3 @error('total_rooms') is-invalid @enderror"
                                value="{{ old('total_rooms', $kost->total_rooms) }}" required min="1">
                            @error('total_rooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-dark">Kamar Kosong / Siap Huni <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="available_rooms"
                                class="form-control rounded-3 @error('available_rooms') is-invalid @enderror"
                                value="{{ old('available_rooms', $kost->available_rooms) }}" required min="0">
                            @error('available_rooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Alamat Lengkap & Patokan Lokasi <span
                                class="text-danger">*</span></label>
                        <textarea name="address" class="form-control rounded-3 @error('address') is-invalid @enderror"
                            rows="2" required>{{ old('address', $kost->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Deskripsi & Peraturan Kost <span
                                class="text-danger">*</span></label>
                        <textarea name="description"
                            class="form-control rounded-3 @error('description') is-invalid @enderror" rows="4"
                            required>{{ old('description', $kost->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Fasilitas Kost Checkboxes -->
                    @php
                    $selectedFacilities = old('facilities', $kost->facilities->pluck('id')->toArray());
                    @endphp
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label small fw-bold text-dark mb-2 d-block">Pilihan Fasilitas yang
                            Tersedia</label>
                        <div class="row g-2">
                            @foreach($facilities as $facility)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="facilities[]"
                                        value="{{ $facility->id }}" id="fac_{{ $facility->id }}"
                                        {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="fac_{{ $facility->id }}">
                                        {{ $facility->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Foto Kost yang Sudah Ada -->
                    @if($kost->images->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Foto yang Sudah Diunggah</label>
                        <div class="row g-2">
                            @foreach($kost->images as $img)
                            <div class="col-md-3 col-6 position-relative">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="img-fluid rounded-3 object-fit-cover w-100" style="height: 120px;"
                                    alt="Foto Kost">
                                <button type="submit" form="delete-image-{{ $img->id }}"
                                    class="btn btn-sm btn-danger rounded-circle p-1 shadow-xs position-absolute top-0 end-0 m-2"
                                    <button type="button"
                                    class="btn btn-sm btn-danger rounded-circle p-1 shadow-xs position-absolute top-0 end-0 m-2 btn-open-delete-photo"
                                    data-form-id="delete-image-{{ $img->id }}" title="Hapus Foto">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Tambah Foto Baru -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Tambah Foto Baru (Opsional)</label>
                        <input type="file" name="images[]" class="form-control rounded-3" multiple accept="image/*">
                        <div class="form-text fs-xs">Format: JPG, JPEG, PNG, WEBP. Maksimal ukuran 5MB per gambar.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('owner.kosts.index') }}" class="btn btn-light border px-4 rounded-3">Batal</a>
                        <button type="submit" class="btn btn-burnt px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

                <!-- Form Hapus Foto Terpisah (Di Luar Form Utama) -->
                @if($kost->images->isNotEmpty())
                @foreach($kost->images as $img)
                <form id="delete-image-{{ $img->id }}" action="{{ route('owner.kosts.image.delete', $img->id) }}"
                    method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Foto (Tema PringKost) -->
<div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg"
            style="border-radius: 24px; background-color: #FFFFFF; border: 1px solid var(--pk-cream-dark) !important;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 56px; height: 56px; background-color: var(--pk-burnt-light); color: var(--pk-burnt);">
                    <i class="fa-solid fa-trash-can fs-4"></i>
                </div>

                <h5 class="fw-extrabold text-dark mb-1" id="deletePhotoModalLabel">Hapus Foto Kost?</h5>
                <p class="text-muted small mb-4" style="line-height: 1.6;">
                    Foto yang dihapus akan hilang dari katalog properti dan tidak bisa dikembalikan.
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light border rounded-pill px-4 fw-semibold"
                        data-bs-dismiss="modal" style="border-color: var(--pk-cream-dark) !important;">
                        Batal
                    </button>
                    <button type="button" id="confirmDeletePhotoBtn"
                        class="btn text-white rounded-pill px-4 fw-semibold shadow-sm"
                        style="background-color: var(--pk-burnt);">
                        <i class="fa-solid fa-check me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('deletePhotoModal');
    const confirmBtn = document.getElementById('confirmDeletePhotoBtn');
    if (!modalEl || !confirmBtn || typeof bootstrap === 'undefined') return;

    const deleteModal = new bootstrap.Modal(modalEl);
    let targetFormId = null;

    document.querySelectorAll('.btn-open-delete-photo').forEach(function(btn) {
        btn.addEventListener('click', function() {
            targetFormId = this.getAttribute('data-form-id');
            deleteModal.show();
        });
    });

    confirmBtn.addEventListener('click', function() {
        if (!targetFormId) return;
        const form = document.getElementById(targetFormId);
        if (form) {
            form.submit();
        }
    });
});
</script>
@endsection