@extends('layouts.users') {{-- Pastikan ini mengacu ke layout utama Anda --}}

@section('content')
<div class="container py-4">
    <div class="text-center mb-4"> {{-- Kurangi margin-bottom --}}
        <h1 class="fw-bold text-primary display-5 mb-2">Selamat Datang, {{ auth()->user()->name }} 👋</h1> {{-- Gunakan display-5 --}}
        <p class="lead text-muted fs-6">Ayo kelola kehadiran siswa dengan mudah dan efisien.</p> {{-- Kurangi ukuran font lead --}}
        @if (auth()->user()->kelas)
            <p class="fw-semibold text-secondary mb-0"> {{-- Kurangi margin-bottom --}}
                Anda adalah Kalas untuk kelas:
                <span class="badge bg-primary fs-7 px-3 py-2">{{ auth()->user()->kelas->nama }} {{ auth()->user()->kelas->jurusan }} {{ auth()->user()->kelas->tingkat }}</span> {{-- Sesuaikan ukuran badge --}}
            </p>
        @else
            <p class="fw-semibold text-warning mb-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Kelas Anda belum diatur. Harap hubungi Admin.
            </p>
        @endif
    </div>

    {{-- Kartu Ringkasan Statistik --}}
    <div class="row g-3 mb-4"> {{-- Kurangi g-spacing dan margin-bottom --}}
        {{-- Kartu Agenda Hari Ini --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInUp animate__faster"> {{-- Animasi lebih cepat --}}
                <div class="card-body text-center d-flex flex-column justify-content-center py-3"> {{-- Kurangi padding-y --}}
                    <i class="bi bi-calendar-check-fill text-info fs-1 mb-2"></i> {{-- Icon lebih kecil (fs-1) --}}
                    <h6 class="card-title fw-bold text-info mb-1">Agenda Hari Ini</h6> {{-- Gunakan h6, kurangi margin --}}
                    <p class="card-text text-muted small mb-2">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                    <hr class="my-2"> {{-- Garis pemisah lebih kecil --}}
                    @if (isset($totalAgendasToday) && $totalAgendasToday > 0)
                        <p class="mb-1 fw-bold text-dark fs-5">{{ $totalAgendaDetailsToday ?? '0' }} <span class="fw-normal text-muted fs-6">mata pelajaran</span></p> {{-- Gabungkan jadi 1 baris --}}
                        <p class="mb-0 text-danger small"><span class="fw-bold">{{ $pendingAgendaDetailsToday ?? '0' }}</span> belum diselesaikan</p> {{-- Ukuran lebih kecil --}}
                    @else
                        <p class="text-muted small mb-0">Belum ada agenda hari ini.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kartu Total Siswa di Kelas --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInUp animate__faster animate__delay-0-1s">
                <div class="card-body text-center d-flex flex-column justify-content-center py-3">
                    <i class="bi bi-people-fill text-primary fs-1 mb-2"></i>
                    <h6 class="card-title fw-bold text-primary mb-1">Siswa di Kelas Anda</h6>
                    <hr class="my-2">
                    @if (auth()->user()->kelas)
                        <p class="mb-1 fw-bold text-dark fs-5">{{ auth()->user()->kelas->jumlah_siswa ?? '0' }} <span class="fw-normal text-muted fs-6">siswa</span></p>
                        <p class="text-muted small mb-0">terdaftar di kelas ini</p>
                    @else
                        <p class="text-muted small mb-0">Tidak ada data siswa.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kartu Agenda Belum Dituntaskan --}}
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInUp animate__faster animate__delay-0-2s">
                <div class="card-body text-center d-flex flex-column justify-content-center py-3">
                    <i class="bi bi-x-circle-fill text-danger fs-1 mb-2"></i>
                    <h6 class="card-title fw-bold text-danger mb-1">Agenda Tertunda</h6>
                    <p class="card-text text-muted small mb-2">
                        Agenda dari hari sebelumnya yang belum selesai.
                    </p>
                    <hr class="my-2">
                    <p class="mb-1 fw-bold text-dark fs-5">{{ $totalPendingAgendas ?? '0' }} <span class="fw-normal text-muted fs-6">agenda</span></p>
                    <p class="text-muted small mb-0">perlu perhatian Anda</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi Cepat --}}
    <div class="row g-3 justify-content-center"> {{-- Kurangi g-spacing --}}
        <div class="col-md-4 col-sm-6"> {{-- Tambah col-sm-6 untuk mobile agar 2 kolom --}}
            <a href="{{ route('agenda.create') }}" class="dashboard-action bg-light text-primary py-3"> {{-- Kurangi padding-y --}}
                <i class="bi bi-journal-plus fs-2 mb-2"></i> {{-- Icon lebih kecil (fs-2) --}}
                <h6 class="mt-0 mb-1">Isi Agenda Harian</h6> {{-- Gunakan h6, tanpa margin-top besar --}}
                <p class="text-muted small mb-0 d-none d-sm-block">Catat setiap pelajaran hari ini.</p> {{-- Sembunyikan deskripsi di mobile --}}
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('agenda.laporan') }}" class="dashboard-action bg-light text-success py-3">
                <i class="bi bi-graph-up fs-2 mb-2"></i>
                <h6 class="mt-0 mb-1">Lihat Rekap Absensi</h6>
                <p class="text-muted small mb-0 d-none d-sm-block">Pantau rekap absensi kelas Anda.</p>
            </a>
        </div>
       <div class="col-md-4 col-sm-6">
        <a href="{{ route('agenda.create') }}" class="dashboard-action bg-light text-secondary py-3"> {{-- Asumsi route untuk mengisi absen mapel --}}
            <i class="bi bi-journal-check fs-2 mb-2"></i> {{-- Mengganti ikon jadi lebih relevan --}}
            <h6 class="mt-0 mb-1">Isi Absen Mata Pelajaran</h6>
            <p class="text-muted small mb-0 d-none d-sm-block">Catat kehadiran Anda pada setiap mata pelajaran.</p>
        </a>
    </div>
    </div>
</div>

{{-- Tambahan CSS Khusus Hover dan Animasi (di section style atau di file CSS terpisah) --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    /* Custom font size for badges */
    .fs-7 {
        font-size: 0.8rem; /* Lebih kecil dari fs-6 */
    }

    .card-body {
        /* Memastikan konten di tengah vertikal dan horizontal */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .dashboard-action {
        display: block;
        text-align: center;
        text-decoration: none;
        padding: 1.5rem 1rem; /* Padding lebih kecil untuk tombol aksi */
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06); /* Bayangan lebih ringan */
        transition: all 0.2s ease-in-out; /* Transisi lebih cepat */
        border: 1px solid rgba(0,0,0,0.05);
    }

    .dashboard-action:hover {
        background-color: #f0f2f5 !important; /* Warna hover sedikit berbeda */
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); /* Bayangan lebih ringan saat hover */
        transform: translateY(-2px); /* Geser ke atas sedikit */
        border-color: rgba(0,0,0,0.08);
    }

    .dashboard-action h6 { /* Target h6 di dalam dashboard-action */
        color: inherit;
        font-weight: 600; /* Sedikit kurang tebal */
        font-size: 1.1rem; /* Ukuran font lebih kecil */
        margin-top: 0.75rem; /* Sesuaikan margin-top */
        margin-bottom: 0.25rem;
    }

    .dashboard-action p {
        font-size: 0.8rem; /* Font size deskripsi lebih kecil */
        line-height: 1.3;
    }

    /* Responsif untuk Mobile */
    @media (max-width: 767.98px) {
        .display-5 {
            font-size: 2rem !important; /* Sesuaikan ukuran display-5 di mobile */
        }
        .lead {
            font-size: 0.95rem;
        }
        .fs-7 {
            font-size: 0.75rem;
        }
        .dashboard-action {
            padding: 1.25rem 0.75rem; /* Padding lebih kecil lagi di mobile */
        }
        .dashboard-action h6 {
            font-size: 1rem; /* Ukuran font h6 lebih kecil di mobile */
        }
        .card-body .fs-5 {
            font-size: 1.3rem !important; /* Angka statistik di mobile */
        }
        .card-body .fs-6 {
            font-size: 0.9rem !important;
        }
        .dashboard-action .d-none.d-sm-block {
            display: none !important; /* Sembunyikan deskripsi tombol di mobile */
        }
    }
</style>
@endpush
@endsection
