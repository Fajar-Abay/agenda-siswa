<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://smkn2sumedang.sch.id/images/profil/sejarah.png">
    <title>{{ config('app.name', 'Smkn 2 Sumedang') }} - Kalas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Warna Dasar SMKN 2 Sumedang */
        :root {
            --smkn2-blue-dark: #003366; /* Biru Gelap untuk Navbar/Header */
            --smkn2-blue-main: #0056b3; /* Biru Utama untuk Sidebar */
            --smkn2-blue-light: #007bff; /* Biru Lebih Terang untuk Hover/Aksen */
        }

        /* Sidebar Flexbox untuk menempatkan tombol logout di bawah */
        #sidebar {
            background-color: var(--smkn2-blue-main); /* Warna biru SMKN 2 Sumedang */
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh; /* Full height */
            box-shadow: 2px 0 5px rgba(0,0,0,0.1); /* Tambah sedikit bayangan */
        }

        /* Tombol Logout berada di bawah */
        #sidebar .mt-auto {
            margin-top: auto;
        }

        /* Styling untuk link menu sidebar */
        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85); /* Sedikit transparan agar lebih lembut */
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
            border-radius: .25rem; /* Sudut sedikit melengkung */
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background-color: var(--smkn2-blue-dark); /* Warna lebih gelap saat hover/aktif */
            color: white;
        }

        #sidebar .nav-link.active {
            font-weight: bold; /* Teks lebih tebal untuk menu aktif */
        }

        /* Responsif untuk Mobile */
        @media (max-width: 767.98px) {
            #sidebar {
                position: fixed; /* Penting untuk mobile agar bisa digeser */
                left: 0;
                top: 0;
                transform: translateX(-100%); /* Sembunyikan di luar layar */
                transition: transform 0.3s ease-in-out;
                width: 250px;
                z-index: 1045; /* Pastikan di atas konten lain */
            }

            #sidebar.show { /* Kelas 'show' akan ditambahkan oleh JS saat dibuka */
                transform: translateX(0);
            }

            /* Mengatur tombol logout di bawah pada tampilan mobile */
            #sidebar .mt-auto {
                margin-top: auto;
            }
        }

        /* Navbar mobile */
        .navbar-dark {
            background-color: var(--smkn2-blue-dark); /* Dark blue for navbar */
        }

        .navbar-dark .navbar-toggler-icon {
            /* Warna ikon toggle akan diatur oleh Bootstrap itu sendiri */
            /* Jika ingin custom, bisa atur background-image disini */
        }

        /* Mobile Menu Button */
        .btn-outline-light {
            color: #fff;
            border-color: #fff;
        }
        .btn-outline-light:hover {
            background-color: var(--smkn2-blue-light);
            border-color: var(--smkn2-blue-light);
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      @stack('styles')

</head>
<body>
    {{-- Navbar untuk mobile --}}
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-light" id="openSidebarBtn">
                ☰ Menu
            </button>
            <span class="navbar-brand mb-0 h1 ms-auto">{{ config('app.name', 'Smkn 2 Sumedang') }}</span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3 col-lg-2 p-0">
                <div id="sidebar"
                    class="position-fixed top-0 start-0 h-100 p-3 d-none d-md-flex flex-column"
                    style="width: 250px;"> {{-- Class bg-dark text-white sudah diganti via CSS --}}

                    {{-- Logo SMKN 2 Sumedang --}}
                    <div class="d-flex justify-content-center mb-4">
                        <img src="https://smkn2sumedang.sch.id/images/profil/sejarah.png" alt="Logo SMKN 2 Sumedang" style="width: 100px;">
                    </div>

                    {{-- Tombol Tutup untuk Mobile --}}
                    <div class="d-flex justify-content-between align-items-center mb-3 d-md-none">
                        <h5 class="mb-0 text-white">Menu</h5>
                        <button id="closeSidebarBtn" class="btn-close btn-close-white"></button>
                    </div>

                    {{-- Menu --}}
                    <h4 class="my-3 d-none d-md-block text-center text-white">Menu Utama</h4>
                    <ul class="nav nav-pills flex-column flex-grow-1">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                            class="nav-link {{ Route::currentRouteName() === 'dashboard' ? 'active' : 'text-white' }}">
                                <i class="bi bi-house me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('agenda.index') }}"
                            class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'agenda.') && Route::currentRouteName() !== 'agenda.laporan' ? 'active' : 'text-white' }}">
                                <i class="bi bi-calendar-event me-2"></i> Agenda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('agenda.laporan') }}"
                            class="nav-link {{ Route::currentRouteName() === "agenda.laporan" ? 'active' : 'text-white' }}">
                               <i class="bi bi-book-half me-2"></i> Rekap Agenda
                            </a>
                        </li>

                        {{-- Menu Manajemen Pengguna (Hanya untuk Admin) --}}
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item mt-3 border-top pt-3">
                                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-1 mb-1 text-white text-uppercase">
                                        <span>Administrasi</span>
                                    </h6>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                    class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'users.') ? 'active' : 'text-white' }}">
                                        <i class="bi bi-people me-2"></i> Manajemen Pengguna
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('mapel.index') }}"
                                    class="nav-link {{ Str::startsWith(Route::currentRouteName(), 'mapel.') ? 'active' : 'text-white' }}">
                                        <i class="bi bi-journal-text me-2"></i> Manajemen Mata Pelajaran
                                    </a>
                                </li>
                                {{-- Tambahkan menu admin lainnya di sini jika ada --}}
                            @endif
                        @endauth
                    </ul>

                    {{-- Tombol Logout di bawah --}}
                    <div class="mt-auto">
                        <div class="dropdown-divider border-secondary my-3"></div> {{-- Garis pemisah --}}
                        <div class="text-white mb-2 text-center">
                            Halo, {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-box-arrow-right me-2"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Konten utama --}}
            <main class="col-md-9 col-lg-10 ms-sm-auto px-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle Sidebar untuk Mobile
        document.getElementById("openSidebarBtn").addEventListener("click", function() {
            document.getElementById("sidebar").classList.add("show"); // Gunakan kelas 'show'
        });

        document.getElementById("closeSidebarBtn").addEventListener("click", function() {
            document.getElementById("sidebar").classList.remove("show"); // Hapus kelas 'show'
        });

        // Pastikan sidebar tersembunyi saat layar diubah dari mobile ke desktop
        $(window).on('resize', function() {
            if ($(window).width() >= 768) {
                $('#sidebar').removeClass('show'); // Sembunyikan jika ukuran desktop
            }
        });

        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5' // Jika Anda menggunakan Bootstrap 5 dan ingin tampilan yang cocok
            });
        });
    </script>
</body>
</html>
