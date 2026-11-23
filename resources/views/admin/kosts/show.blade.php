@extends('layouts.admin')

@section('title', 'Detail Kost: ' . $kost->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Top Action Bar -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.kosts.index') }}" class="btn btn-sm btn-light border rounded-pill px-2.5">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="h4 fw-bold text-dark mb-0">{{ $kost->name }}</h2>
                <x-admin.status-badge :status="$kost->status" />
            </div>
            <p class="text-muted small mb-0">Diajukan oleh: <strong>{{ $kost->user->name ?? 'Pemilik' }}</strong> pada {{ $kost->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($kost->status !== 'approved')
                <button type="button" class="btn btn-success rounded-pill fw-bold btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#detailApproveModal">
                    <i class="fa-solid fa-check me-1"></i> Setujui Kost
                </button>
            @endif
            @if($kost->status !== 'rejected')
                <button type="button" class="btn btn-danger rounded-pill fw-bold btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#detailRejectModal">
                    <i class="fa-solid fa-xmark me-1"></i> Tolak Kost
                </button>
            @endif
            <a href="{{ route('kost.show', $kost->id) }}" target="_blank" class="btn btn-outline-dark rounded-pill btn-sm px-3">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Halaman Publik
            </a>
        </div>
    </div>

    <!-- Reason Banner if Rejected -->
    @if($kost->status === 'rejected' && $kost->rejection_reason)
    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation fs-3 text-danger"></i>
        <div>
            <h6 class="fw-bold mb-1">Listing ini Ditolak oleh Administrator</h6>
            <div class="small">Alasan: {{ $kost->rejection_reason }}</div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- Main Column: Photos & Info -->
        <div class="col-lg-8">
            <!-- Galeri Foto Kost -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-regular fa-images me-2 text-danger"></i> Galeri Foto Properti</h5>
                @if($kost->images->isNotEmpty())
                    <div class="row g-2">
                        @foreach($kost->images as $img)
                            <div class="col-md-4 col-6">
                                <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="img-fluid rounded-3 object-fit-cover w-100 shadow-xs" style="height: 180px;" alt="Foto Kost">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted bg-light rounded-3">
                        <i class="fa-solid fa-image fs-1 mb-2 opacity-50"></i>
                        <p class="small mb-0">Pemilik belum mengunggah foto untuk kost ini.</p>
                    </div>
                @endif
            </div>

            <!-- Detail Deskripsi & Alamat -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">Deskripsi Lengkap</h5>
                <p class="text-secondary" style="white-space: pre-line; line-height: 1.7;">{{ $kost->description }}</p>

                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-2">Alamat & Lokasi</h5>
                <p class="text-secondary mb-0"><i class="fa-solid fa-location-dot me-2 text-danger"></i> {{ $kost->address }}</p>
            </div>

            <!-- Fasilitas Tersedia -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3">Fasilitas yang Dipilih</h5>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($kost->facilities as $fac)
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-check text-success me-1.5"></i> {{ $fac->name }}
                        </span>
                    @empty
                        <span class="text-muted small">Tidak ada data fasilitas yang ditandai.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Side Column: Info Pemilik & Spesifikasi -->
        <div class="col-lg-4">
            <!-- Ringkasan Tarif & Kamar -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Spesifikasi Properti</h6>
                <div class="mb-3">
                    <span class="text-muted small d-block">Harga Sewa Bulanan</span>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($kost->price, 0, ',', '.') }}</h3>
                </div>
                <div class="row g-2 border-top pt-3">
                    <div class="col-6">
                        <span class="text-muted small d-block">Tipe Kost</span>
                        <span class="fw-bold text-capitalize badge bg-light text-dark border">{{ $kost->type }}</span>
                    </div>
                    <div class="col-6">
                        <span class="text-muted small d-block">Sisa Kamar</span>
                        <span class="fw-bold text-dark">{{ $kost->available_rooms }} / {{ $kost->total_rooms }} Kamar</span>
                    </div>
                </div>
            </div>

            <!-- Informasi Pemilik Kost -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Informasi Pemilik (Mitra)</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.1rem;">
                        {{ strtoupper(substr($kost->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $kost->user->name ?? 'Tidak Diketahui' }}</h6>
                        <span class="text-muted small">{{ $kost->user->email ?? '-' }}</span>
                    </div>
                </div>
                <ul class="list-unstyled small mb-0 border-top pt-3">
                    <li class="d-flex justify-content-between py-1">
                        <span class="text-muted">Nomor WhatsApp:</span>
                        <span class="fw-semibold">{{ $kost->user->phone ?? 'Belum terisi' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-1">
                        <span class="text-muted">Status Akun:</span>
                        <span class="badge bg-light text-dark border text-capitalize">{{ $kost->user->status ?? 'active' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-1">
                        <span class="text-muted">Terdaftar Sejak:</span>
                        <span class="fw-semibold">{{ $kost->user->created_at ? $kost->user->created_at->format('d M Y') : '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Approve -->
<div class="modal fade" id="detailApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-success"><i class="fa-solid fa-circle-check me-1"></i> Konfirmasi Persetujuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                Yakin ingin menyetujui kost <strong>{{ $kost->name }}</strong>? Listing ini akan langsung aktif di pencarian publik PringKost.
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.kost.approve', $kost->id) }}" method="POST" class="m-0">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success rounded-pill px-3 fw-bold">Ya, Setujui Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Reject -->
<div class="modal fade" id="detailRejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.kost.reject', $kost->id) }}" method="POST" class="m-0">
            @csrf
            @method('PATCH')
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Tolak Listing Kost</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="form-control rounded-3" rows="3" placeholder="Sebutkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Tolak Listing</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
