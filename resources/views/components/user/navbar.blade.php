@props([
    'title' => 'Dashboard Pencari Kost',
    'breadcrumbs' => []
])

@php
    $unreadCount = Auth::check() ? Auth::user()->unreadNotifications()->count() : 0;
@endphp

<header class="user-topbar bg-white border-bottom sticky-top px-4 py-2.5">
    <div class="d-flex align-items-center justify-content-between">
        <!-- Left: Toggle & Breadcrumb / Title -->
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light border d-lg-none" id="sidebarToggleBtn" type="button" aria-label="Toggle Sidebar">
                <i class="fa-solid fa-bars fs-6"></i>
            </button>

            <div>
                @if(!empty($breadcrumbs))
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 fs-xs" style="font-size: 0.78rem;">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                            @foreach($breadcrumbs as $label => $url)
                                @if($loop->last)
                                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $label }}</li>
                                @else
                                    <li class="breadcrumb-item"><a href="{{ $url }}" class="text-decoration-none text-muted">{{ $label }}</a></li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                @endif
                <h1 class="h5 fw-bold mb-0 text-dark">{{ $title }}</h1>
            </div>
        </div>

        <!-- Right: Notifications & Profile -->
        <div class="d-flex align-items-center gap-3">
            <!-- Notification Bell Icon Button -->
            <a href="{{ route('user.notifications.index') }}" class="btn btn-light border-0 position-relative rounded-circle p-2 text-secondary" title="Notifikasi">
                <i class="fa-solid fa-bell fs-5"></i>
                @if($unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </a>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="btn btn-light border-0 d-flex align-items-center gap-2 p-1 pe-2 rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 0.88rem;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="fw-semibold small d-none d-sm-inline">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down fs-xs text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2 p-2" style="min-width: 220px;">
                    <li class="px-3 py-2 border-bottom mb-1">
                        <div class="fw-bold small text-dark">{{ Auth::user()->name }}</div>
                        <div class="text-muted fs-xs" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                        <span class="badge bg-light text-secondary border rounded-pill mt-1" style="font-size: 0.65rem;">PENCARI KOST</span>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-1.5 small" href="{{ route('user.profile.edit') }}">
                            <i class="fa-solid fa-user-circle me-2 text-muted"></i>Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-1.5 small" href="{{ route('user.favorites.index') }}">
                            <i class="fa-solid fa-heart me-2 text-muted"></i>Kost Favorit
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-1.5 small" href="{{ route('user.bookings.index') }}">
                            <i class="fa-solid fa-calendar-check me-2 text-muted"></i>Pemesanan Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2 py-1.5 small" href="{{ route('kost.index') }}" target="_blank">
                            <i class="fa-solid fa-earth-asia me-2 text-muted"></i>Katalog Publik
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger rounded-2 py-1.5 small">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar (Logout)
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
