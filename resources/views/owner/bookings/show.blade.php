@extends('layouts.owner')

@section('title', 'Detail Pesanan: #BK-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('owner.bookings.index') }}" class="btn btn-sm btn-light border rounded-pill px-2.5">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="h4 fw-bold text-dark mb-0">Pesanan #BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</h2>
                <x-admin.status-badge :status="$booking->status" />
            </div>
            <p class="text-muted small mb-0">Tanggal Pengajuan: {{ $booking->created_at->format('d F Y, H:i') }} WIB</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($booking->status === 'pending')
                <button type="button" class="btn btn-success rounded-pill fw-bold btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#detailApproveModal">
                    <i class="fa-solid fa-check me-1"></i> Setujui Sewa
                </button>
                <button type="button" class="btn btn-danger rounded-pill fw-bold btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#detailRejectModal">
                    <i class="fa-solid fa-xmark me-1"></i> Tolak Sewa
                </button>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Rincian Pesanan Sewa -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-receipt me-2 text-warning"></i> Rincian Pemesanan Sewa</h5>
                
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0 small">
                        <tbody>
                            <tr class="border-bottom">
                                <td class="text-muted py-2" style="width: 200px;">Mulai Sewa</td>
                                <td class="fw-bold py-2">{{ \Carbon\Carbon::parse($booking->start_date)->format('d F Y') }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="text-muted py-2">Durasi Sewa</td>
                                <td class="fw-bold py-2">{{ $booking->duration_months }} Bulan</td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="text-muted py-2">Tarif Sewa Properti</td>
                                <td class="fw-semibold py-2">Rp {{ number_format($booking->kost->price ?? 0, 0, ',', '.') }} / bulan</td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="text-muted py-2">Total Biaya Sewa</td>
                                <td class="fw-bold text-success fs-5 py-2">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted py-2">Status Pesanan</td>
                                <td class="py-2"><x-admin.status-badge :status="$booking->status" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Properti Kost yang Dipesan -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-hotel me-2 text-danger"></i> Properti Kost yang Dipesan</h5>
                <div class="d-flex align-items-start gap-3">
                    @if($booking->kost && $booking->kost->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $booking->kost->images->first()->image_path) }}" class="rounded-3 object-fit-cover shadow-xs" style="width: 100px; height: 100px;" alt="Foto Kost">
                    @else
                        <div class="rounded-3 bg-light text-muted d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="fa-solid fa-image fs-3"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="fw-bold text-dark mb-1">{{ $booking->kost->name ?? 'Kost Dihapus' }}</h6>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $booking->kost->address ?? '-' }}</p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border text-capitalize small">{{ $booking->kost->type ?? '-' }}</span>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle small">Sisa {{ $booking->kost->available_rooms ?? 0 }} Kamar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Informasi Calon Penyewa -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <h6 class="text-uppercase text-secondary fw-bold small mb-3">Informasi Calon Penyewa</h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-light border fw-bold text-dark d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; font-size: 1.2rem;">
                        {{ strtoupper(substr($booking->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $booking->user->name ?? 'Pengguna' }}</h6>
                        <span class="text-muted small">{{ $booking->user->email ?? '-' }}</span>
                    </div>
                </div>

                <ul class="list-group list-group-flush small border-top pt-2">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Nomor WhatsApp:</span>
                        <span class="fw-bold">{{ $booking->user->phone ?? 'Belum diisi' }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Terdaftar Sejak:</span>
                        <span>{{ $booking->user->created_at ? $booking->user->created_at->format('d M Y') : '-' }}</span>
                    </li>
                </ul>

                @if(!empty($booking->user->phone))
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $booking->user->phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <div class="mt-3">
                        <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($booking->user->name) }},%20saya%20pemilik%20kost%20{{ urlencode($booking->kost->name ?? '') }}%20dari%20PringKost." target="_blank" class="btn btn-success w-100 rounded-pill fw-bold btn-sm py-2">
                            <i class="fa-brands fa-whatsapp me-1"></i> Hubungi Calon Penyewa
                        </a>
                    </div>
                @endif
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
                Yakin ingin menyetujui pesanan dari <strong>{{ $booking->user->name ?? 'Pencari' }}</strong>?
                <p class="text-muted small mt-2 mb-0">Kamar siap huni pada kost akan otomatis dikurangi 1.</p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('owner.bookings.update', $booking->id) }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-success rounded-pill px-3 fw-bold">Ya, Setujui</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Reject -->
<div class="modal fade" id="detailRejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Tolak Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                Yakin ingin menolak pengajuan booking dari <strong>{{ $booking->user->name ?? 'Pencari' }}</strong>?
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('owner.bookings.update', $booking->id) }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-danger rounded-pill px-3 fw-bold">Tolak Pesanan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
