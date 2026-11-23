@extends('layouts.user')

@section('title', 'Profil Akun Saya')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Pengaturan Profil Saya</h2>
            <p class="text-muted small mb-0">Kelola informasi kontak WhatsApp, alamat email, dan keamanan akun pencari kost Anda.</p>
        </div>
        <div>
            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Summary Card -->
        <div class="col-lg-4">
            <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-4 text-center">
                <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 76px; height: 76px; font-size: 2rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill small fw-semibold">
                        PENCARI KOST
                    </span>
                    <x-admin.status-badge :status="$user->status ?? 'active'" />
                </div>

                <ul class="list-group list-group-flush text-start border-top pt-3 small">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Nomor WhatsApp:</span>
                        <span class="fw-bold">{{ $user->phone ?? '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Total Pemesanan:</span>
                        <span class="fw-bold">{{ $user->bookings->count() }} Kali</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Bergabung Pada:</span>
                        <span>{{ $user->created_at->format('d M Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form Edit Profil & Kata Sandi -->
        <div class="col-lg-8">
            <div class="card border border-light-subtle shadow-xs rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-pen me-2 text-secondary"></i> Perbarui Data Akun</h5>
                
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Nomor WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789" required>
                        <div class="form-text fs-xs">Nomor ini digunakan pemilik kost untuk menghubungi konfirmasi pemesanan sewa Anda.</div>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-lock me-2 text-secondary"></i> Ubah Kata Sandi (Opsional)</h5>
                    <p class="text-muted small mb-3">Kosongkan kolom ini jika Anda tidak bermaksud mengganti kata sandi.</p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="form-control rounded-3 @error('current_password') is-invalid @enderror" placeholder="Masukkan kata sandi akun saat ini">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Kata Sandi Baru</label>
                            <input type="password" name="new_password" class="form-control rounded-3 @error('new_password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                            @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control rounded-3" placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <button type="submit" class="btn btn-burnt px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
