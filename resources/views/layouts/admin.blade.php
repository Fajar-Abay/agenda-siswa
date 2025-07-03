<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Smkn 2 Sumedang') }} - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Base Colors & Variables */
        :root {
            --smkn2-blue-dark: #003366; /* Deeper blue for main background, navbar */
            --smkn2-blue-main: #0056b3; /* Primary blue for active states, accents */
            --smkn2-blue-light: #007bff; /* Lighter blue for hover effects */
            --smkn2-red-danger: #dc3545; /* Red for danger/logout */
            --smkn2-red-dark: #b02a37; /* Darker red for hover */
        }

        /* Sidebar Styling */
        #sidebar {
            background: linear-gradient(180deg, var(--smkn2-blue-dark) 0%, var(--smkn2-blue-main) 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            height: 100vh;
            padding-bottom: 1.5rem;
            width: 250px; /* Lebar sidebar desktop diperlebar */
            transition: width 0.3s ease;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1050;
        }

        #sidebar a.nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease-in-out;
            border-left: 5px solid transparent;
            margin-bottom: 0.25rem;
            border-radius: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.95rem; /* Font size tetap agar tidak terpotong */
        }

        #sidebar a.nav-link i {
            margin-right: 0.75rem;
        }

        #sidebar a.nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-left-color: var(--smkn2-blue-light);
            box-shadow: inset 5px 0 15px 2px rgba(0, 123, 255, 0.2);
        }

        /* Active Link Effect */
        #sidebar a.nav-link.active {
            background-color: var(--smkn2-blue-light);
            color: white !important;
            border-left-color: #ffffff;
            box-shadow: inset 5px 0 15px 2px rgba(0, 123, 255, 0.7);
            font-weight: 600;
            position: relative;
        }
        /* Optional: Add a small triangle indicator for active item */
        #sidebar a.nav-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 2px;
            margin-right: -4px;
        }


        /* Logout Button */
        .btn-logout {
            background-color: var(--smkn2-red-danger);
            color: white;
            font-weight: 600;
            padding: 0.85rem 1rem;
            border-radius: 0.5rem;
            border: none;
            width: 100%;
            font-size: 1.05rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .btn-logout:hover,
        .btn-logout:focus {
            background-color: var(--smkn2-red-dark);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            outline: none;
            color: white;
            transform: translateY(-1px);
        }

        /* Navbar Mobile */
        .navbar-dark {
            background-color: var(--smkn2-blue-dark);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-dark .btn-outline-light {
            color: white;
            border-color: white;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: normal;
        }

        .navbar-dark .btn-outline-light:hover {
            background-color: var(--smkn2-blue-light);
            border-color: var(--smkn2-blue-light);
        }

        /* Responsive Mobile Sidebar */
        @media (max-width: 767.98px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                width: 280px !important; /* Lebar sidebar mobile diperlebar */
                padding-top: 1rem;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            .main-content-wrapper {
                margin-left: 0 !important;
            }
        }

        /* Logo */
        .sidebar-logo img {
            width: 80px;
            margin-bottom: 1.5rem;
        }

        .sidebar-user-info {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            padding: 0 1rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.5rem;
        }
        .sidebar-user-info strong {
            color: #fff;
            display: block;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    {{-- Navbar Mobile --}}
    <nav class="navbar navbar-dark d-md-none">
        <div class="container-fluid">
            <button
                class="btn btn-outline-light"
                id="openSidebarBtn"
                aria-label="Open menu sidebar"
            >
                <i class="bi bi-list"></i> Menu
            </button>
            <span class="navbar-brand mb-0 h1 ms-auto text-white">{{ config('app.name', 'Smkn 2 Sumedang') }}</span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3 col-lg-2 p-0">
                <div
                    id="sidebar"
                    class="position-fixed top-0 start-0 h-100 p-3 d-none d-md-flex flex-column"
                    style="z-index: 1045"
                >
                    {{-- Logo --}}
                    <div class="sidebar-logo d-flex justify-content-center mb-4">
                        <img
                            src="https://smkn2sumedang.sch.id/images/profil/sejarah.png"
                            alt="Logo SMKN 2 Sumedang"
                        />
                    </div>

                    {{-- Close Button (Mobile) --}}
                    <div class="d-flex justify-content-end mb-3 d-md-none">
                        <button id="closeSidebarBtn" class="btn btn-close btn-close-white" aria-label="Close menu"></button>
                    </div>

                    {{-- Menu --}}
                    <h5 class="mt-2 mb-3 text-white text-center d-none d-md-block">Admin Navigation</h5>
                    <ul class="nav nav-pills flex-column flex-grow-1">
                        <li class="nav-item">
                            <a
                            href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ Route::currentRouteName() === 'admin.dashboard' ? 'active' : '' }}"
                            ><i class="bi bi-house-door-fill"></i> Dashboard</a
                            >
                        </li>

                        {{-- Guru Menu --}}
                        <li class="nav-item">
                            <a
                            href="{{ route('guru.index') }}"
                            class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'guru.') ? 'active' : '' }}"
                            ><i class="bi bi-person-circle"></i> Manajemen Guru</a
                            >
                        </li>

                        {{-- Mata Pelajaran Menu --}}
                        <li class="nav-item">
                            <a
                            href="{{ route('mapel.index') }}"
                            class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'mapel.') ? 'active' : '' }}"
                            ><i class="bi bi-book-fill"></i> Manajemen Mapel</a
                            >
                        </li>

                        {{-- Users Menu --}}
                        <li class="nav-item">
                            <a
                            href="{{ route('users.index') }}"
                            class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'users.') ? 'active' : '' }}"
                            ><i class="bi bi-people-fill"></i> Manajemen Pengguna</a
                            >
                        </li>

                        {{-- Rekap Menu --}}
                        <li class="nav-item">
                            <a
                            href="{{ route('admin.laporan') }}"
                            class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'admin.laporan') ? 'active' : '' }}"
                            ><i class="bi bi-bar-chart-line-fill"></i> Laporan & Rekap</a
                            >
                        </li>
                    </ul>

                    {{-- Logout --}}
                    <div class="mt-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-logout btn-primary" aria-label="Logout">
                                <i class="bi bi-box-arrow-right me-2"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <main class="col-md-9 col-lg-10 ms-sm-auto px-4 py-4 main-content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle Sidebar (Mobile)
        document.getElementById("openSidebarBtn").addEventListener("click", function () {
            document.getElementById("sidebar").classList.add("show");
        });

        document.getElementById("closeSidebarBtn").addEventListener("click", function () {
            document.getElementById("sidebar").classList.remove("show");
        });

        // Close sidebar on screen resize (from mobile to desktop)
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) { // md breakpoint
                document.getElementById('sidebar').classList.remove('show');
                document.getElementById('sidebar').classList.add('d-md-flex');
            } else {
                 document.getElementById('sidebar').classList.add('d-none');
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
    
        openBtn?.addEventListener('click', () => {
            sidebar.classList.remove('d-none');
        });
    
        closeBtn?.addEventListener('click', () => {
            sidebar.classList.add('d-none');
        });
    });
    </script>

    @stack('scripts')
</body>
</html>
