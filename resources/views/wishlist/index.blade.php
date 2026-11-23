@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Kost Favorit Saya</h2>
            <p class="text-muted">Daftar kost yang telah Anda simpan.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        @if($wishlists->isEmpty())
        <div class="col-md-12">
            <div class="alert alert-info text-center py-4">
                <p class="mb-2">Belum ada kost yang ditambahkan ke daftar favorit.</p>
                <a href="{{ route('kost.index') }}" class="btn btn-primary btn-sm">Cari Kost Sekarang</a>
            </div>
        </div>
        @else
        @foreach($wishlists as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <span
                        class="badge bg-{{ $item->kost->type == 'putra' ? 'primary' : ($item->kost->type == 'putri' ? 'danger' : 'success') }} text-capitalize mb-2">
                        Kost {{ $item->kost->type }}
                    </span>
                    <h5 class="card-title fw-bold text-dark">{{ $item->kost->name }}</h5>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i>
                        {{ $item->kost->address }}</p>
                    <h6 class="text-success fw-bold">Rp {{ number_format($item->kost->price, 0, ',', '.') }} <small
                            class="text-muted fw-normal">/bulan</small></h6>
                </div>
                <div class="card-footer bg-white border-0 pb-3 d-flex justify-content-between gap-2">
                    <a href="{{ route('kost.show', $item->kost->id) }}"
                        class="btn btn-outline-primary btn-sm w-50">Detail</a>
                    <form action="{{ route('wishlist.toggle', $item->kost->id) }}" method="POST" class="w-50">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-50">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection