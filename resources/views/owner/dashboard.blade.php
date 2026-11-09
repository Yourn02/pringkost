@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Dashboard Mitra (Pemilik Kost)</h2>
            <p class="text-muted mb-0">Kelola properti kost Anda di Pringsewu.</p>
        </div>
        <a href="{{ route('owner.kost.create') }}" class="btn btn-dark">
            <i class="fa-solid fa-plus me-1"></i> Tambah Kost Baru
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        @forelse($kosts as $kost)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span
                            class="badge bg-{{ $kost->type == 'putra' ? 'primary' : ($kost->type == 'putri' ? 'danger' : 'success') }} text-capitalize">
                            Kost {{ $kost->type }}
                        </span>
                        <!-- Status Verifikasi Admin -->
                        @if($kost->status == 'approved')
                        <span class="badge bg-success">Disetujui</span>
                        @elseif($kost->status == 'rejected')
                        <span class="badge bg-danger">Ditolak</span>
                        @else
                        <span class="badge bg-warning text-dark">Pending / Menunggu</span>
                        @endif
                    </div>

                    <h5 class="card-title fw-bold">{{ $kost->name }}</h5>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i> {{ $kost->address }}
                    </p>
                    <h4 class="text-success fw-bold">Rp {{ number_format($kost->price, 0, ',', '.') }} <small
                            class="text-muted fs-6">/bulan</small></h4>
                    <p class="badge bg-info text-dark mb-0">Sisa Kamar: {{ $kost->available_rooms }} /
                        {{ $kost->total_rooms }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted fs-5">Anda belum mendaftarkan properti kost apa pun.</p>
            <a href="{{ route('owner.kost.create') }}" class="btn btn-outline-dark">Daftarkan Kost Pertama Anda</a>
        </div>
        @endforelse
    </div>
</div>
@endsection