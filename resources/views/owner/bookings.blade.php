@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Kelola Pengajuan Sewa Masuk</h2>
            <p class="text-muted">Daftar calon penyewa yang mengajukan sewa pada properti kost Anda.</p>
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
                <p class="mb-0">Belum ada pengajuan sewa yang masuk untuk properti Anda.</p>
            </div>
            @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pencari</th>
                                    <th>Nama Kost</th>
                                    <th>Mulai Sewa</th>
                                    <th>Durasi</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi Konfirmasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $booking->user->name ?? 'User' }}</div>
                                        <small class="text-muted">{{ $booking->user->email ?? '-' }}</small>
                                    </td>
                                    <td>{{ $booking->kost->name ?? 'Kost Dihapus' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
                                    <td>{{ $booking->duration_months }} Bulan</td>
                                    <td class="fw-bold text-success">Rp
                                        {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($booking->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                        @else
                                        <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($booking->status == 'pending')
                                        <form action="{{ route('owner.bookings.update', $booking->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success mb-1">Setujui</button>
                                        </form>
                                        <form action="{{ route('owner.bookings.update', $booking->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger mb-1">Tolak</button>
                                        </form>
                                        @else
                                        <span class="text-muted fs-6">Selesai / Aksi Dikunci</span>
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