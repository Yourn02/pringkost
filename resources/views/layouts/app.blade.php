<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'PringKost') }} - Cari Kost di Pringsewu</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        /* Design System Palette */
        --pk-cream: #F3EEE3;
        --pk-cream-light: #FAF7F2;
        --pk-cream-dark: #E5DDD0;

        --pk-black: #111111;
        --pk-black-soft: #222222;
        --pk-black-muted: #3A3A3A;

        --pk-burnt: #D9541E;
        --pk-burnt-hover: #B84214;
        --pk-burnt-light: #FCEEE8;
        --pk-burnt-muted: #F7DCD0;

        --pk-emerald: #059669;
    }

    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: var(--pk-cream) !important;
        color: var(--pk-black);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Topbar / Navigation */
    .navbar-pringkost {
        background-color: #F1F1F1 !important;
        border-bottom: 1px solid var(--pk-cream-dark);
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }

    .navbar-brand-pringkost {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    /* Buttons Design Guidelines */
    .btn-burnt {
        background-color: var(--pk-burnt) !important;
        color: #FFFFFF !important;
        font-weight: 700;
        border-radius: 16px;
        padding: 0.55rem 1.4rem;
        border: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(217, 84, 30, 0.25);
    }

    .btn-burnt:hover {
        background-color: var(--pk-burnt-hover) !important;
        color: #FFFFFF !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(217, 84, 30, 0.35);
    }

    .btn-neutral {
        background-color: var(--pk-cream-dark) !important;
        color: var(--pk-black) !important;
        font-weight: 600;
        border-radius: 16px;
        padding: 0.55rem 1.25rem;
        border: 1px solid var(--pk-cream-dark);
        transition: all 0.2s ease;
    }

    .btn-neutral:hover {
        background-color: #d6ccbe !important;
        color: var(--pk-black) !important;
    }

    .btn-whatsapp {
        background-color: var(--pk-emerald) !important;
        color: #FFFFFF !important;
        font-weight: 700;
        border-radius: 16px;
        padding: 0.75rem 1.5rem;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
    }

    .btn-whatsapp:hover {
        background-color: #047857 !important;
        color: #FFFFFF !important;
        transform: translateY(-1px);
    }

    /* Cards & Containers */
    .card-editorial {
        background-color: #FFFFFF;
        border: 1px solid var(--pk-cream-dark);
        border-radius: 24px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
    }

    .card-editorial:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(17, 17, 17, 0.08) !important;
    }

    .badge-editorial {
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 9999px;
        padding: 0.35rem 0.75rem;
    }

    .badge-type-putra {
        background-color: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
    }

    .badge-type-putri {
        background-color: #FDF2F8;
        color: #BE185D;
        border: 1px solid #FBCFE8;
    }

    .badge-type-campur {
        background-color: #ECFDF5;
        color: #047857;
        border: 1px solid #A7F3D0;
    }

    /* Form Inputs */
    .form-control-editorial,
    .form-select-editorial {
        background-color: var(--pk-cream-light);
        border: 1.5px solid var(--pk-cream-dark);
        border-radius: 14px;
        padding: 0.6rem 1rem;
        color: var(--pk-black);
        font-size: 0.95rem;
    }

    .form-control-editorial:focus,
    .form-select-editorial:focus {
        background-color: #FFFFFF;
        border-color: var(--pk-burnt);
        box-shadow: 0 0 0 3px rgba(217, 84, 30, 0.15);
        outline: none;
    }

    /* Footer */
    .footer-pringkost {
        background-color: #F1F1F1;
        color: var(--pk-black-muted);
        margin-top: auto;
        border-top: 1px solid var(--pk-cream-dark);
    }
    </style>
</head>

<body>
    <!-- Navbar with Warm Editorial & Modern Minimalist Style -->
    <nav class="navbar navbar-expand-lg navbar-pringkost sticky-top shadow-sm">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand-pringkost" href="{{ url('/') }}">
                <svg class="flex-shrink-0" width="34" height="34" viewBox="0 0 48 48" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 4L4 20H10V42C10 43.1046 10.8954 44 12 44H36C37.1046 44 38 43.1046 38 42V20H44L24 4Z"
                        fill="#D9541E" />
                    <path d="M20 44V26C20 24.8954 20.8954 24 22 24H26C27.1046 24 28 24.8954 28 26V44H20Z"
                        fill="#111111" />
                    <rect x="29" y="16" width="5" height="5" rx="1" fill="#FAF7F2" fill-opacity="0.9" />
                </svg>
                <span class="fs-4 fw-extrabold tracking-tight text-dark mb-0" style="letter-spacing: -0.5px;">
                    Pring<span style="color: var(--pk-burnt);">Kost</span>
                </span>
            </a>

            <button class="navbar-toggler border-0 text-dark" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <i class="fa-solid fa-bars fs-5"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-secondary px-3 {{ request()->is('/') ? 'text-dark fw-bold' : '' }}"
                            href="{{ url('/') }}">
                            Jelajah Kost
                        </a>
                    </li>

                    @auth
                    @if(auth()->user()->role === 'mahasiswa')
                    <li class="nav-item">
                        <a class="nav-link text-secondary px-3 {{ request()->is('wishlist*') ? 'text-dark fw-bold' : '' }}"
                            href="{{ route('wishlist.index') }}">
                            <i class="fa-regular fa-heart me-1 text-danger"></i> Favorit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary px-3 {{ request()->is('my-bookings*') ? 'text-dark fw-bold' : '' }}"
                            href="{{ route('booking.index') }}">
                            Riwayat Sewa
                        </a>
                    </li>
                    @elseif(auth()->user()->role === 'pemilik')
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold" style="color: var(--pk-burnt);"
                            href="{{ route('owner.dashboard') }}">
                            <i class="fa-solid fa-hotel me-1"></i> Dashboard Mitra
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary px-3 {{ request()->is('owner/bookings*') ? 'text-dark fw-bold' : '' }}"
                            href="{{ route('owner.bookings.index') }}">
                            Pesanan Masuk
                        </a>
                    </li>
                    @elseif(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link text-info fw-bold px-3" href="{{ route('admin.dashboard') }}">
                            <i class="fa-solid fa-shield-halved me-1"></i> Panel Admin
                        </a>
                    </li>
                    @endif

                    <!-- User Profile Dropdown -->
                    <li class="nav-item dropdown ms-lg-2">
                        <a id="navbarDropdown"
                            class="nav-link dropdown-toggle text-dark d-flex align-items-center gap-2 py-1 px-3 rounded-pill"
                            style="background-color: var(--pk-cream-dark);" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <span class="fw-semibold small">{{ Auth::user()->name }}</span>
                            <span class="badge rounded-pill text-capitalize"
                                style="background-color: var(--pk-burnt); font-size: 0.65rem;">
                                {{ Auth::user()->role }}
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-2"
                            style="border-radius: 16px; background-color: #FFFFFF;">
                            <a class="dropdown-item py-2 rounded-3 text-danger fw-semibold" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @else
                    <!-- Guest Actions -->
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link text-dark fw-semibold px-3" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-1">
                        <a class="btn btn-burnt btn-sm px-4" href="{{ route('register') }}">Daftar</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Global Alerts -->
    @if(session('success'))
    <div class="container mt-4">
        <div class="alert alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert"
            style="background-color: #ECFDF5; color: #065F46; border-radius: 16px;">
            <i class="fa-solid fa-circle-check fs-5 text-emerald"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-pringkost py-4 mt-5">
        <div class="container">
            <div class="row align-items-center py-2">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <div class="d-inline-flex align-items-center gap-2 mb-2">
                        <svg width="24" height="24" viewBox="0 0 48 48" fill="none">
                            <path
                                d="M24 4L4 20H10V42C10 43.1046 10.8954 44 12 44H36C37.1046 44 38 43.1046 38 42V20H44L24 4Z"
                                fill="#D9541E" />
                            <path d="M20 44V26C20 24.8954 20.8954 24 22 24H26C27.1046 24 28 24.8954 28 26V44H20Z"
                                fill="#111111" />
                        </svg>
                        <span class="fw-extrabold text-dark">Pring<span
                                style="color: var(--pk-burnt);">Kost</span></span>
                    </div>
                    <p class="text-muted small mb-0">Platform Informasi Kost di Pringsewu.</p>
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    &copy; {{ date('Y') }} PringKost. Warm Editorial & Modern Minimalist.
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>