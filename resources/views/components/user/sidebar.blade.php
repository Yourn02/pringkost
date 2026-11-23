@php
    $unreadCount = Auth::check() ? Auth::user()->unreadNotifications()->count() : 0;
    $favoriteCount = Auth::check() ? \App\Models\Wishlist::where('user_id', Auth::id())->count() : 0;
@endphp

<aside id="userSidebar" class="user-sidebar d-flex flex-column flex-shrink-0 bg-white border-end">
    <!-- Brand Header -->
    <div class="sidebar-header d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
        <a href="{{ route('user.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <svg class="h-8 w-8 flex-shrink-0" width="34" height="34" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M24 4L4 20H10V42C10 43.1046 10.8954 44 12 44H36C37.1046 44 38 43.1046 38 42V20H44L24 4Z" fill="#D9541E"/>
                <path d="M20 44V26C20 24.8954 20.8954 24 22 24H26C27.1046 24 28 24.8954 28 26V44H20Z" fill="#FFFFFF"/>
                <rect x="29" y="16" width="5" height="5" rx="1" fill="#FAF7F2" fill-opacity="0.9"/>
            </svg>
            <div class="d-flex flex-column">
                <span class="fw-bolder fs-5 lh-1 text-dark">
                    Pring<span style="color: #D9541E;">Kost</span>
                </span>
            </div>
        </a>
        <button type="button" class="btn btn-sm btn-light border d-lg-none" id="sidebarCloseBtn" aria-label="Tutup Sidebar">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-nav-wrapper flex-grow-1 overflow-y-auto px-3 py-3">
        <div class="text-uppercase text-secondary fw-bold px-3 mb-2" style="font-size: 0.68rem; letter-spacing: 1px;">Aktivitas Utama</div>
        <ul class="nav nav-pills flex-column gap-1 mb-4">
            <!-- 1. Dashboard -->
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" 
                   class="nav-link user-nav-link d-flex align-items-center gap-3 py-2.5 px-3 rounded-3 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-columns nav-icon fs-5"></i>
                    <span class="nav-text fw-semibold">Dashboard</span>
                </a>
            </li>

            <!-- 2. Cari Kost -->
            <li class="nav-item">
                <a href="{{ route('user.kosts.index') }}" 
                   class="nav-link user-nav-link d-flex align-items-center gap-3 py-2.5 px-3 rounded-3 {{ request()->routeIs('user.kosts.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-magnifying-glass-location nav-icon fs-5"></i>
                    <span class="nav-text fw-semibold">Cari Kost</span>
                </a>
            </li>

            <!-- 3. Kost Favorit (Wishlist) -->
            <li class="nav-item">
                <a href="{{ route('user.favorites.index') }}" 
                   class="nav-link user-nav-link d-flex align-items-center justify-content-between py-2.5 px-3 rounded-3 {{ request()->routeIs('user.favorites.*') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-heart nav-icon fs-5"></i>
                        <span class="nav-text fw-semibold">Kost Favorit</span>
                    </div>
                    @if($favoriteCount > 0)
                        <span class="badge bg-light text-danger border rounded-pill fw-bold fs-xs px-2">
                            {{ $favoriteCount }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- 4. Pemesanan Saya -->
            <li class="nav-item">
                <a href="{{ route('user.bookings.index') }}" 
                   class="nav-link user-nav-link d-flex align-items-center gap-3 py-2.5 px-3 rounded-3 {{ request()->routeIs('user.bookings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-check nav-icon fs-5"></i>
                    <span class="nav-text fw-semibold">Pemesanan Saya</span>
                </a>
            </li>

            <!-- 5. Notifikasi -->
            <li class="nav-item">
                <a href="{{ route('user.notifications.index') }}" 
                   class="nav-link user-nav-link d-flex align-items-center justify-content-between py-2.5 px-3 rounded-3 {{ request()->routeIs('user.notifications.*') ? 'active' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-bell nav-icon fs-5"></i>
                        <span class="nav-text fw-semibold">Notifikasi</span>
                    </div>
                    @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill fw-bold fs-xs px-2">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- 6. Profil Saya -->
            <li class="nav-item">
                <a href="{{ route('user.profile.edit') }}" 
                   class="nav-link user-nav-link d-flex align-items-center gap-3 py-2.5 px-3 rounded-3 {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-circle nav-icon fs-5"></i>
                    <span class="nav-text fw-semibold">Profil Saya</span>
                </a>
            </li>
        </ul>

        <div class="text-uppercase text-secondary fw-bold px-3 mb-2" style="font-size: 0.68rem; letter-spacing: 1px;">Katalog Eksternal</div>
        <ul class="nav nav-pills flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('kost.index') }}" target="_blank" class="nav-link user-nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 text-muted">
                    <i class="fa-solid fa-arrow-up-right-from-square nav-icon fs-6"></i>
                    <span class="nav-text small">Katalog Publik PringKost</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Profile & Logout Footer -->
    <div class="sidebar-footer border-top p-3 bg-light-subtle">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold text-dark text-truncate small" title="{{ Auth::user()->name }}">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-muted text-truncate fs-xs" style="font-size: 0.72rem;">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Keluar / Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
