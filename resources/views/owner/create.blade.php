@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-3">Tambah Properti Kost Baru</h3>
                    <p class="text-muted mb-4">Isi data secara lengkap. Listing akan ditinjau oleh Admin sebelum tampil
                        secara publik.</p>

                    <form action="{{ route('owner.kost.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kost</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Contoh: Kost Melati Pringsewu">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jenis Kost</label>
                                <select name="type" class="form-select" required>
                                    <option value="putra">Putra</option>
                                    <option value="putri">Putri</option>
                                    <option value="campur">Campur</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Harga per Bulan (Rp)</label>
                                <input type="number" name="price" class="form-control" required
                                    placeholder="Contoh: 500000">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Total Seluruh Kamar</label>
                                <input type="number" name="total_rooms" class="form-control" required
                                    placeholder="Contoh: 10">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kamar Tersedia Saat Ini</label>
                                <input type="number" name="available_rooms" class="form-control" required
                                    placeholder="Contoh: 3">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap & Lokasi</label>
                            <textarea name="address" class="form-control" rows="2" required
                                placeholder="Contoh: Jl. Ahmad Yani No. 10, dekat Kampus UAP Pringsewu"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi & Fasilitas Umum</label>
                            <textarea name="description" class="form-control" rows="4" required
                                placeholder="Jelaskan ukuran kamar, fasilitas kamar mandi dalam/luar, aturan kost, dll."></textarea>
                        </div>

                        <!-- Pilihan Fasilitas (Master Fasilitas) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Fasilitas Kost</label>
                            <div class="row">
                                @foreach($facilities as $facility)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="facilities[]"
                                            value="{{ $facility->id }}" id="facility_{{ $facility->id }}">
                                        <label class="form-check-label" for="facility_{{ $facility->id }}">
                                            {{ $facility->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Upload Multi-Foto -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Unggah Foto Kost (Bisa pilih lebih dari satu)</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                            <div class="form-text">Format: JPG, PNG. Maksimal ukuran per foto 2MB.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-dark px-4">Simpan & Ajukan Listing</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection