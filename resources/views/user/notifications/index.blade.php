@extends('layouts.user')

@section('title', 'Notifikasi Saya')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="h4 fw-bold text-dark mb-0">Pusat Notifikasi</h2>
                @if($unreadCount > 0)
                    <span class="badge bg-danger rounded-pill">{{ $unreadCount }} Belum Dibaca</span>
                @endif
            </div>
            <p class="text-muted small mb-0">Pemberitahuan resmi mengenai status persetujuan atau penolakan pengajuan sewa kamar kost Anda.</p>
        </div>
        <div class="d-flex gap-2">
            @if($unreadCount > 0)
                <form action="{{ route('user.notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                        <i class="fa-solid fa-check-double me-1"></i> Tandai Semua Sudah Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Notification List -->
    <div class="card border border-light-subtle shadow-xs rounded-4 bg-white overflow-hidden">
        <div class="list-group list-group-flush">
            @forelse($notifications as $item)
                @php
                    $data = $item->data;
                    $isUnread = is_null($item->read_at);
                    $iconClass = match($data['type'] ?? 'info') {
                        'success' => 'bg-success-subtle text-success',
                        'danger' => 'bg-danger-subtle text-danger',
                        'warning' => 'bg-warning-subtle text-warning-emphasis',
                        default => 'bg-light text-secondary',
                    };
                @endphp
                <div class="list-group-item p-3.5 {{ $isUnread ? 'bg-light-subtle' : '' }} border-bottom">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $iconClass }}" style="width: 44px; height: 44px; font-size: 1.1rem;">
                            <i class="fa-solid {{ $data['icon'] ?? 'fa-bell' }}"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                <h6 class="fw-bold text-dark mb-0 {{ $isUnread ? 'text-primary' : '' }}">
                                    {{ $data['title'] ?? 'Notifikasi' }}
                                    @if($isUnread)
                                        <span class="badge bg-danger rounded-pill fs-xs ms-1">Baru</span>
                                    @endif
                                </h6>
                                <span class="text-muted fs-xs">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-secondary small mb-2">{{ $data['message'] ?? '' }}</p>
                            
                            <div class="d-flex align-items-center gap-2">
                                @if(!empty($data['url']))
                                    <a href="{{ $data['url'] }}" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 fs-xs fw-semibold">
                                        Lihat Terkait <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
                                
                                @if($isUnread)
                                    <form action="{{ route('user.notifications.read', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-xs btn-light border rounded-pill px-2.5 py-1 fs-xs text-muted">
                                            <i class="fa-solid fa-check me-1"></i> Tandai Dibaca
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('user.notifications.destroy', $item->id) }}" method="POST" class="d-inline ms-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-link text-danger p-0 fs-xs" title="Hapus Notifikasi">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="py-4 text-muted">
                        <i class="fa-solid fa-bell-slash fs-1 mb-2 opacity-50"></i>
                        <h6 class="fw-bold text-dark">Belum Ada Notifikasi</h6>
                        <p class="small mb-0">Semua konfirmasi persetujuan atau penolakan booking akan muncul di sini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Menampilkan {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }} dari {{ $notifications->total() }} notifikasi
                    </div>
                    <div>{{ $notifications->links() }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
