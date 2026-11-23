@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Pemilik Kost</h2>
            <p class="text-muted">Kelola listing properti dan status ketersediaan kamar Anda di sini.</p>
        </div>
        <a href="{{ route('owner.kosts.create') }}" class="btn btn-warning fw-semibold">
            <i class="fa-solid fa-plus me-1"></i> Tambah Kost Baru
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kost</th>
                            <th>Jenis</th>
                            <th>Harga / Bulan</th>
                            <th>Kamar Tersedia</th>
                            <th>Status Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kosts as $kost)
                        <tr>
                            <td class="fw-semibold">{{ $kost->name }}</td>
                            <td class="text-capitalize">{{ $kost->type }}</td>
                            <td>Rp {{ number_format($kost->price, 0, ',', '.') }}</td>
                            <td>
                                <!-- Form Update Sisa Kamar Real-time -->
                                <form action="{{ route('owner.kosts.updaterooms', $kost->id) }}" method="POST"
                                    class="d-flex align-items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="available_rooms" value="{{ $kost->available_rooms }}"
                                        class="form-control form-control-sm me-2" style="width: 70px;" min="0"
                                        max="{{ $kost->total_rooms }}">
                                    <button type="submit" class="btn btn-sm btn-outline-dark" title="Perbarui Kamar"><i
                                            class="fa-solid fa-check"></i></button>
                                </form>
                                <small class="text-muted">Dari total: {{ $kost->total_rooms }}</small>
                            </td>
                            <td>
                                @if($kost->status == 'approved')
                                <span class="badge bg-success">Disetujui</span>
                                @elseif($kost->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @else
                                <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('owner.kosts.destroy', $kost->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kost ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i
                                            class="fa-solid fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada listing kost yang Anda
                                daftarkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection