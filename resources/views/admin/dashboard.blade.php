@extends('layouts.admin')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Welcome Banner -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white position-relative overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-8">

                <h2 class="fw-bold mb-1 text-dark">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-muted mb-0">
                    Pantau listing properti kost se-Pringsewu, verifikasi pemilik baru, kelola master fasilitas, dan amankan interaksi platform secara real-time.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('admin.kosts.index', ['status' => 'pending']) }}" class="btn btn-warning rounded-3 fw-bold btn-sm px-3 shadow-sm">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Verifikasi Kost ({{ $pendingKostsCount }})
                    </a>
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-dark rounded-3 fw-semibold btn-sm px-3">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Fasilitas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 8 Grid Kartu Statistik Dinamis Sesuai Database -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Pengguna (Pemilik & Pencari) -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Total Pengguna" 
                :value="$totalUsers" 
                icon="fa-users" 
                color="dark"
                link="{{ route('admin.users.index') }}"
                description="Total pemilik kost & pencari terdaftar"
            />
        </div>

        <!-- 2. Total Pemilik Kost -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Total Pemilik Kost" 
                :value="$totalOwners" 
                icon="fa-user-tie" 
                color="purple"
                link="{{ route('admin.users.index', ['role' => 'pemilik']) }}"
                description="Mitra pemilik properti kost"
            />
        </div>

        <!-- 3. Total Pencari Kost -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Total Pencari Kost" 
                :value="$totalSearchers" 
                icon="fa-magnifying-glass-location" 
                color="blue"
                link="{{ route('admin.users.index', ['role' => 'pencari']) }}"
                description="Pencari kost & calon penyewa"
            />
        </div>

        <!-- 4. Total Listing Kost -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Total Listing Kost" 
                :value="$totalKosts" 
                icon="fa-hotel" 
                color="burnt"
                link="{{ route('admin.kosts.index') }}"
                description="Semua data properti tersimpan"
            />
        </div>

        <!-- 5. Kost Menunggu Verifikasi -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Menunggu Verifikasi" 
                :value="$pendingKostsCount" 
                icon="fa-clock" 
                color="amber"
                link="{{ route('admin.kosts.index', ['status' => 'pending']) }}"
                description="Perlu tindakan persetujuan admin"
                :badge="$pendingKostsCount > 0 ? 'Perlu Ditinjau' : 'Terkendali'"
            />
        </div>

        <!-- 6. Kost Disetujui -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Kost Disetujui" 
                :value="$approvedKostsCount" 
                icon="fa-circle-check" 
                color="emerald"
                link="{{ route('admin.kosts.index', ['status' => 'approved']) }}"
                description="Tampil aktif di katalog publik"
            />
        </div>

        <!-- 7. Kost Ditolak -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Kost Ditolak" 
                :value="$rejectedKostsCount" 
                icon="fa-circle-xmark" 
                color="burnt"
                link="{{ route('admin.kosts.index', ['status' => 'rejected']) }}"
                description="Pengajuan kost tidak memenuhi syarat"
            />
        </div>

        <!-- 8. Total Fasilitas -->
        <div class="col-sm-6 col-xl-3">
            <x-admin.stat-card 
                title="Total Fasilitas" 
                :value="$totalFacilities" 
                icon="fa-cubes-stacked" 
                color="dark"
                link="{{ route('admin.facilities.index') }}"
                description="Master fasilitas properti kost"
            />
        </div>
    </div>

    <!-- Section: Pengajuan Kost Terbaru Menunggu Verifikasi -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="p-2 rounded-3 bg-warning-subtle text-warning-emphasis">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </span>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Pengajuan Kost Terbaru</h5>
                            <span class="text-muted small">Daftar kost berstatus pending yang membutuhkan keputusan verifikasi</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.kosts.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-secondary small text-uppercase">
                                    <th class="ps-4">Kost</th>
                                    <th>Pemilik</th>
                                    <th>Harga & Tipe</th>
                                    <th>Alamat</th>
                                    <th class="text-end pe-4">Aksi Cepat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPendingKosts as $kost)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2.5">
                                            @if($kost->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $kost->images->first()->image_path) }}" alt="{{ $kost->name }}" class="rounded-3 object-fit-cover" width="46" height="46">
                                            @else
                                                <div class="rounded-3 bg-light text-muted d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.kosts.show', $kost->id) }}" class="fw-bold text-dark text-decoration-none hover-underline">
                                                    {{ $kost->name }}
                                                </a>
                                                <div class="text-muted fs-xs">Diajukan: {{ $kost->created_at->format('d M Y, H:i') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small text-dark">{{ $kost->user->name ?? 'Tidak Ada' }}</div>
                                        <div class="text-muted fs-xs">{{ $kost->user->phone ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small">Rp {{ number_format($kost->price, 0, ',', '.') }}<span class="text-muted fw-normal">/bln</span></div>
                                        <span class="badge bg-light text-secondary border text-capitalize" style="font-size: 0.7rem;">{{ $kost->type }}</span>
                                    </td>
                                    <td>
                                        <div class="small text-muted text-truncate" style="max-width: 180px;" title="{{ $kost->address }}">
                                            {{ $kost->address }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.kosts.show', $kost->id) }}" class="btn btn-sm btn-light border rounded-pill px-2.5" title="Lihat Detail">
                                                <i class="fa-solid fa-eye text-secondary"></i>
                                            </a>
                                            <!-- Approve Button Modal Trigger -->
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#approveModal{{ $kost->id }}" title="Setujui">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <!-- Reject Button Modal Trigger -->
                                            <button type="button" class="btn btn-sm btn-danger rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $kost->id }}" title="Tolak">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>

                                        <!-- Modal Approve -->
                                        <div class="modal fade" id="approveModal{{ $kost->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <div class="modal-content border-0 shadow rounded-4">
                                                    <div class="modal-header border-bottom">
                                                        <h5 class="modal-title fw-bold text-success">
                                                            <i class="fa-solid fa-circle-check me-1"></i> Konfirmasi Persetujuan
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        Apakah Anda yakin ingin menyetujui listing <strong>{{ $kost->name }}</strong>?
                                                        <p class="text-muted small mt-2 mb-0">Setelah disetujui, informasi kost ini akan langsung ditampilkan dan dapat dipesan oleh pengguna di Pringsewu.</p>
                                                    </div>
                                                    <div class="modal-footer border-top">
                                                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('admin.kost.approve', $kost->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success rounded-pill px-3 fw-bold">Ya, Setujui Listing</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Reject -->
                                        <div class="modal fade" id="rejectModal{{ $kost->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <form action="{{ route('admin.kost.reject', $kost->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <div class="modal-header border-bottom">
                                                            <h5 class="modal-title fw-bold text-danger">
                                                                <i class="fa-solid fa-circle-xmark me-1"></i> Tolak Pengajuan Kost
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <p class="mb-2">Anda akan menolak listing <strong>{{ $kost->name }}</strong> milik <em>{{ $kost->user->name ?? '-' }}</em>.</p>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-dark">Alasan Penolakan (Opsional)</label>
                                                                <textarea name="rejection_reason" class="form-control rounded-3" rows="3" placeholder="Contoh: Foto tidak jelas, data alamat tidak valid, atau fasilitas tidak sesuai deskripsi..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top">
                                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Tolak Pengajuan</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-3">
                                            <div class="mb-2 text-muted">
                                                <i class="fa-solid fa-clipboard-check fs-1 text-success opacity-75"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Semua Pengajuan Telah Ditinjau</h6>
                                            <p class="text-muted small mb-0">Tidak ada pengajuan kost yang sedang menunggu verifikasi saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Pintasan & Aktivitas -->
        <div class="col-xl-4">
            <!-- Shortcut Cepat -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3">
                <h6 class="fw-bold text-dark mb-3 px-2">Aksi Cepat Administrator</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.kosts.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-hotel me-2 text-danger"></i> Kelola Listing Kost</span>
                        <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                    </a>
                    <a href="{{ route('admin.facilities.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-cubes-stacked me-2 text-warning"></i> Kelola Master Fasilitas</span>
                        <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-users me-2 text-primary"></i> Kelola Data Pengguna</span>
                        <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                    </a>
                    <a href="{{ route('kost.index') }}" target="_blank" class="btn btn-light border text-start d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <span class="small fw-semibold"><i class="fa-solid fa-arrow-up-right-from-square me-2 text-success"></i> Buka Website Publik</span>
                        <i class="fa-solid fa-chevron-right fs-xs text-muted"></i>
                    </a>
                </div>
            </div>
@endsection
