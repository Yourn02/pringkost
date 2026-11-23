@extends('layouts.admin')

@section('title', 'Detail Pengguna: ' . $user->name)

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light border rounded-pill px-2.5">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2 class="h4 fw-bold text-dark mb-0">{{ $user->name }}</h2>
                <x-admin.status-badge :status="$user->status ?? 'active'" />
            </div>
            <p class="text-muted small mb-0">ID Pengguna: #{{ $user->id }} &bull; Bergabung sejak {{ $user->created_at->format('d F Y') }}</p>
        </div>
        <div>
            @if($user->id !== Auth::id() && $user->role !== 'admin')
                @if($user->status !== 'active')
                    <form action="{{ route('admin.users.status.update', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="btn btn-success rounded-pill btn-sm px-3 fw-bold">
                            <i class="fa-solid fa-circle-check me-1"></i> Aktifkan Akun
                        </button>
                    </form>
                @endif
                @if($user->status !== 'suspended')
                    <form action="{{ route('admin.users.status.update', $user->id) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="suspended">
                        <button type="submit" class="btn btn-warning rounded-pill btn-sm px-3 fw-bold" onclick="return confirm('Tangguhkan akun ini?')">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Suspend
                        </button>
                    </form>
                @endif
                @if($user->status !== 'blocked')
                    <form action="{{ route('admin.users.status.update', $user->id) }}" method="POST" class="d-inline ms-1">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="blocked">
                        <button type="submit" class="btn btn-danger rounded-pill btn-sm px-3 fw-bold" onclick="return confirm('Blokir akun ini?')">
                            <i class="fa-solid fa-ban me-1"></i> Blokir
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 text-center">
                <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px; font-size: 1.8rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-2">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-secondary-subtle text-secondary border text-capitalize px-3 py-1.5 rounded-pill">
                        Role: {{ $user->role }}
                    </span>
                    <x-admin.status-badge :status="$user->status ?? 'active'" />
                </div>

                <ul class="list-group list-group-flush text-start border-top pt-3 small">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Nomor WhatsApp:</span>
                        <span class="fw-bold">{{ $user->phone ?? 'Belum Diatur' }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Verifikasi Email:</span>
                        <span>{!! $user->email_verified_at ? '<span class="text-success fw-bold"><i class="fa-solid fa-check"></i> Terverifikasi</span>' : '<span class="text-muted">Belum</span>' !!}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span class="text-muted">Terdaftar Pada:</span>
                        <span>{{ $user->created_at->format('d M Y, H:i') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Activity & Records -->
        <div class="col-lg-8">
            @if($user->role === 'pemilik')
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-hotel me-2 text-danger"></i> Daftar Listing Kost Pemilik ({{ $user->kosts->count() }})</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>Nama Kost</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->kosts as $kost)
                                    <tr>
                                        <td class="fw-bold">{{ $kost->name }}</td>
                                        <td>Rp {{ number_format($kost->price, 0, ',', '.') }}</td>
                                        <td><x-admin.status-badge :status="$kost->status" /></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.kosts.show', $kost->id) }}" class="btn btn-sm btn-light border rounded-pill">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-3 text-muted">Pemilik belum mendaftarkan properti kost.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Riwayat Pemesanan Kost -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-receipt me-2 text-warning"></i> Riwayat Booking Pengguna ({{ $user->bookings->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Kost Dipesan</th>
                                <th>Mulai Sewa</th>
                                <th>Durasi</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->bookings as $booking)
                                <tr>
                                    <td class="fw-semibold">{{ $booking->kost->name ?? 'Kost Dihapus' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
                                    <td>{{ $booking->duration_months }} Bulan</td>
                                    <td class="fw-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td><x-admin.status-badge :status="$booking->status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada riwayat booking.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
