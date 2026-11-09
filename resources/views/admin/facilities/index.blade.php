@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Kelola Master Fasilitas Kost</h2>
            <p class="text-muted">Tambah atau hapus data fasilitas yang tersedia untuk properti kost.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Form Tambah Fasilitas -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 p-3">
                <h5 class="fw-bold mb-3">Tambah Fasilitas Baru</h5>
                <form action="{{ route('admin.facilities.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Fasilitas</label>
                        <input type="text" name="name" class="form-control" placeholder="Cth: AC, WiFi, Kasur..."
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-sm">Simpan Fasilitas</button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Fasilitas -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Nama Fasilitas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($facilities as $index => $facility)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $facility->name }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Yakin ingin menghapus fasilitas ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada data fasilitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection