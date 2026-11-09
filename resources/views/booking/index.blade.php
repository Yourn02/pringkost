@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Riwayat Pengajuan Sewa Kost</h2>
            <p class="text-muted">Daftar pesanan atau status pengajuan kamar kost yang Anda ajukan.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if($bookings->isEmpty())
            <div class="alert alert-info text-center py-4">
                <p class="mb-2">Anda belum pernah mengajukan pemesanan kost.</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">Cari Kost Sekarang</a>
            </div>
            @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Kost</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Durasi</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $booking->kost->name ?? 'Kost Dihapus' }}</div>
                                        <small class="text-muted">{{ $booking->kost->address ?? '-' }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
                                    <td>{{ $booking->duration_months }} Bulan</td>
                                    <td class="fw-bold text-success">Rp
                                        {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                        @elseif($booking->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                        @else
                                        <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($booking->kost)
                                        <a href="{{ route('kost.show', $booking->kost->id) }}"
                                            class="btn btn-sm btn-outline-primary">Lihat Kost</a>
                                        @else
                                        <span class="text-muted fs-6">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection