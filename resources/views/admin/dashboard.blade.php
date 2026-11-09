@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dashboard Administrator</h2>
        <p class="text-muted">Verifikasi listing kost dari pemilik dan kelola master fasilitas platform PringKost.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Verifikasi Kost Menunggu (Pending) -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> Kost Menunggu Verifikasi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kost</th>
                            <th>Pemilik</th>
                            <th>Jenis & Harga</th>
                            <th>Alamat</th>
                            <th class="text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingKosts as $kost)
                        <tr>
                            <td class="fw-semibold">{{ $kost->name }}</td>
                            <td>{{ $kost->user->name ?? 'Tidak diketahui' }}</td>
                            <td>
                                <span class="text-capitalize badge bg-secondary">{{ $kost->type }}</span><br>
                                Rp {{ number_format($kost->price, 0, ',', '.') }}
                            </td>
                            <td>{{ $kost->address }}</td>
                            <td class="text-center">
                                <form action="{{ route('admin.kost.approve', $kost->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm"><i
                                            class="fa-solid fa-check"></i> Setuju</button>
                                </form>
                                <form action="{{ route('admin.kost.reject', $kost->id) }}" method="POST"
                                    class="d-inline ms-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                            class="fa-solid fa-xmark"></i> Tolak</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">Tidak ada pengajuan kost yang menunggu
                                verifikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kelola Master Fasilitas -->
    <div class="row">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">Tambah Master Fasilitas</div>
                <div class="card-body">
                    <form action="{{ route('admin.facility.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Fasilitas</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Contoh: AC, WiFi, Kamar Mandi Dalam">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Simpan Fasilitas</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light fw-bold">Daftar Fasilitas Tersedia</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($facilities as $facility)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $facility->name }}
                            <form action="{{ route('admin.facility.destroy', $facility->id) }}" method="POST"
                                onsubmit="return confirm('Hapus fasilitas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"><i
                                        class="fa-solid fa-trash"></i></button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection