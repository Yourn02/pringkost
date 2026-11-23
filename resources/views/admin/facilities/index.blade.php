@extends('layouts.admin')

@section('title', 'Master Fasilitas Kost')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Master Data Fasilitas Kost</h2>
            <p class="text-muted small mb-0">Kelola daftar opsi fasilitas yang dapat dipilih pemilik kost saat membuat atau mengedit listing properti.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Tambah Fasilitas Baru -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="p-2 rounded-3 bg-primary-subtle text-primary">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">Tambah Fasilitas</h5>
                </div>
                <form action="{{ route('admin.facilities.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Nama Fasilitas <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="Contoh: AC, Wi-Fi Cepat, Kamar Mandi Dalam..." value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-burnt w-100 py-2">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Fasilitas
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Fasilitas -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <!-- Search Toolbar -->
                <div class="p-3 border-bottom bg-light-subtle">
                    <form method="GET" action="{{ route('admin.facilities.index') }}" class="row g-2 align-items-center">
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama fasilitas..." value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-sm-4 d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-grow-1">Cari</button>
                            @if($search)
                                <a href="{{ route('admin.facilities.index') }}" class="btn btn-light border" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary small text-uppercase">
                                <th class="ps-4" style="width: 70px;">NO</th>
                                <th>Nama Fasilitas</th>
                                <th>Digunakan Oleh</th>
                                <th>Dibuat</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($facilities as $facility)
                            <tr>
                                <td class="ps-4 fw-bold text-muted fs-xs"> #{{ $facilities->FirstItem() + $loop->index }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $facility->name }}</span>
                                </td>
                                <td>
                                    @if($facility->kosts_count > 0)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                            <i class="fa-solid fa-hotel me-1"></i> {{ $facility->kosts_count }} Kost
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border rounded-pill">Belum digunakan</span>
                                    @endif
                                </td>
                                <td class="text-muted fs-xs">
                                    {{ $facility->created_at ? $facility->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Edit Fasilitas">
                                            <i class="fa-solid fa-pen-to-square text-secondary"></i>
                                        </a>

                                        @if($facility->kosts_count > 0)
                                            <!-- Fasilitas digunakan oleh listing kost: Tombol disable aman agar tidak merusak relasi -->
                                            <button type="button" class="btn btn-sm btn-light text-muted border rounded-pill px-2.5" disabled title="Fasilitas tidak dapat dihapus karena masih digunakan oleh {{ $facility->kosts_count }} kost.">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#deleteFacilityModal{{ $facility->id }}" title="Hapus Fasilitas">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if($facility->kosts_count == 0)
                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteFacilityModal{{ $facility->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered text-start">
                                            <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content border-0 shadow rounded-4">
                                                    <div class="modal-header border-bottom">
                                                        <h5 class="modal-title fw-bold text-danger">
                                                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Hapus Fasilitas
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        Apakah Anda yakin ingin menghapus fasilitas <strong>{{ $facility->name }}</strong>?
                                                        <p class="text-muted small mt-2 mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                                                    </div>
                                                    <div class="modal-footer border-top">
                                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Ya, Hapus</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-3 text-muted">
                                        <i class="fa-solid fa-cubes-stacked fs-2 mb-2 opacity-50"></i>
                                        <h6 class="fw-bold text-dark">Fasilitas Tidak Ditemukan</h6>
                                        <p class="small mb-0">Belum ada fasilitas yang cocok dengan pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($facilities->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Menampilkan {{ $facilities->firstItem() }} - {{ $facilities->lastItem() }} dari {{ $facilities->total() }} fasilitas
                        </div>
                        <div>
                            {{ $facilities->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
