<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PringKost - Cari Kost di Pringsewu</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --pk-dark: #212529;
        /* Warna 1: Dominan (Navbar, Footer, Teks Utama) */
        --pk-light: #F8F9FA;
        /* Warna 2: Background / Netral */
        --pk-accent: #FFC107;
        /* Warna 3: Aksen / Tombol / Highlight */
    }

    body {
        background-color: var(--pk-light) !important;
        color: var(--pk-dark);
    }

    .bg-custom-dark {
        background-color: var(--pk-dark) !important;
    }

    .bg-custom-light {
        background-color: var(--pk-light) !important;
    }

    /* Tombol atau aksen khusus menggunakan warna ke-3 */
    .btn-custom-accent,
    .badge-custom-accent {
        background-color: var(--pk-accent) !important;
        color: #212529 !important;
        font-weight: 600;
    }

    .text-custom-accent {
        color: var(--pk-accent) !important;
    }
    </style>
</head>


<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fa-solid fa-house-chimney text-warning me-2"></i>PringKost
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                            href="{{ url('/') }}">Beranda</a></li>

                    @auth
                    <!-- Menu Khusus Mahasiswa / Pencari Kost -->
                    @if(auth()->user()->role === 'mahasiswa')
                    <li class="nav-item"><a class="nav-link {{ request()->is('wishlist*') ? 'active' : '' }}"
                            href="{{ route('wishlist.index') }}">Favorit Saya</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('my-bookings*') ? 'active' : '' }}"
                            href="{{ route('booking.index') }}">Riwayat Sewa</a></li>

                    <!-- Menu Khusus Pemilik Kost -->
                    @elseif(auth()->user()->role === 'pemilik')
                    <li class="nav-item"><a
                            class="nav-link text-warning {{ request()->is('owner*') ? 'active fw-bold' : '' }}"
                            href="{{ route('owner.bookings') }}">Kelola Pesanan Masuk</a></li>

                    <!-- Menu Khusus Admin -->
                    @elseif(auth()->user()->role === 'admin')
                    <li class="nav-item"><a
                            class="nav-link text-info fw-semibold {{ request()->is('admin*') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                    @endif

                    <!-- User Info & Logout Button -->
                    <li class="nav-item dropdown ms-2">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle text-light" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span
                                class="badge bg-secondary text-capitalize ms-1">{{ Auth::user()->role }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Keluar (Logout)
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @else
                    <!-- Menu Tamu (Belum Login) -->
                    <li class="nav-item ms-2"><a class="btn btn-outline-light btn-sm px-3"
                            href="{{ route('login') }}">Masuk</a></li>
                    <li class="nav-item ms-2"><a class="btn btn-warning btn-sm text-dark fw-semibold px-3"
                            href="{{ route('register') }}">Daftar</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <!-- Konten Utama -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5 shadow-lg">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} PringKost. Platform Informasi Kost Universitas Aisyah Pringsewu &
                Sekitarnya.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>