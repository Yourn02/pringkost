<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - PringKost Administrator</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            /* Warm Editorial & Clean Admin Theme */
            --pk-cream: #F3EEE3;
            --pk-cream-light: #FAF7F2;
            --pk-cream-dark: #E5DDD0;
            --pk-black: #111111;
            --pk-black-soft: #222222;
            --pk-burnt: #D9541E;
            --pk-burnt-hover: #B84214;
            --pk-burnt-subtle: #FCEEE8;
            --pk-emerald: #059669;
            --admin-sidebar-width: 260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8F9FA;
            color: var(--pk-black);
            min-height: 100vh;
        }

        .fs-xs {
            font-size: 0.75rem;
        }

        /* Admin Wrapper Layout */
        .admin-layout-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: all 0.25s ease-in-out;
            background: #FFFFFF;
        }

        .admin-main-viewport {
            flex: 1;
            margin-left: var(--admin-sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.25s ease-in-out;
            background-color: #F8F9FA;
        }

        /* Navigation Links */
        .admin-nav-link {
            color: #4B5563;
            font-weight: 500;
            transition: all 0.18s ease;
            text-decoration: none;
        }
        .admin-nav-link:hover {
            color: var(--pk-burnt);
            background-color: var(--pk-burnt-subtle);
        }
        .admin-nav-link.active {
            color: var(--pk-burnt) !important;
            background-color: var(--pk-burnt-subtle) !important;
            font-weight: 700;
            border-left: 3px solid var(--pk-burnt);
        }
        .admin-nav-link .nav-icon {
            width: 24px;
            text-align: center;
        }

        .admin-sub-link {
            color: #6B7280;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .admin-sub-link:hover, .admin-sub-link.active-sub {
            color: var(--pk-burnt);
            font-weight: 700;
        }

        /* Primary Button */
        .btn-burnt {
            background-color: var(--pk-burnt) !important;
            color: #FFFFFF !important;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-burnt:hover {
            background-color: var(--pk-burnt-hover) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 12px rgba(217, 84, 30, 0.25);
        }

        /* Stat Card Animation */
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06) !important;
        }

        /* Submenu Chevron Rotation */
        [aria-expanded="true"] .submenu-arrow {
            transform: rotate(180deg);
        }
        .transition-transform {
            transition: transform 0.2s ease;
        }

        /* Responsive Mobile Drawer */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: calc(-1 * var(--admin-sidebar-width));
            }
            .admin-sidebar.show-mobile {
                margin-left: 0;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            }
            .admin-main-viewport {
                margin-left: 0;
            }
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.4);
                z-index: 1035;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-layout-wrapper">
        <!-- Sidebar Navigation -->
        @include('components.admin.sidebar')
        <div id="sidebarBackdrop" class="sidebar-backdrop"></div>

        <!-- Main Viewport -->
        <div class="admin-main-viewport">
            <!-- Topbar Header -->
            @include('components.admin.navbar', [
                'title' => $title ?? 'Dashboard Administrator',
                'breadcrumbs' => $breadcrumbs ?? []
            ])

            <!-- Flash Alerts -->
            <main class="flex-grow-1 p-3 p-md-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Terdapat kesalahan pengisian data:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Dynamic Page Content -->
                @yield('content')
            </main>

            <!-- Admin Footer -->
            <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <div>&copy; {{ date('Y') }} <strong class="text-dark">PringKost</strong>. Administrator Control Hub.</div>
                    <div class="text-secondary fs-xs">Pringsewu Kost Management & Monitoring System</div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            const backdrop = document.getElementById('sidebarBackdrop');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('show-mobile');
                if (backdrop) backdrop.classList.add('show');
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('show-mobile');
                if (backdrop) backdrop.classList.remove('show');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
