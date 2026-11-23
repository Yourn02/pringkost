@extends('layouts.admin')

@section('title', 'Edit Fasilitas: ' . $facility->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Edit Fasilitas</h4>
                        <span class="text-muted small">Perbarui data master nama fasilitas kost</span>
                    </div>
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-sm btn-light border rounded-pill px-3">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Nama Fasilitas <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $facility->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-light border small text-muted mb-4">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>
                        Fasilitas ini saat ini terhubung dengan <strong>{{ $facility->kosts_count }} listing kost</strong>.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-burnt px-4">
                            <i class="fa-solid fa-check me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-light border rounded-3 px-3">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
