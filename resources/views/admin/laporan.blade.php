@extends('layouts.admin') {{-- Ensure this points to your admin layout --}}

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4 text-primary"><i class="bi bi-bar-chart-line-fill me-2"></i>Laporan Agenda Harian</h1>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan') }}" class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <label for="kelas" class="form-label text-muted">Kalas (Kelas)</label>
                    <select name="kelas" id="kelas" class="form-select">
                        <option value="">Semua Kalas</option>
                        @foreach ($kelasList as $kalasName)
                            {{-- IMPORTANT: Changed to $kalasName as the value from pluck('nama_kelas') --}}
                            <option value="{{ $kalasName }}" {{ request('kelas') == $kalasName ? 'selected' : '' }}>
                                {{ $kalasName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-lg-2">
                    <label for="bulan" class="form-label text-muted">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @foreach (range(1, 12) as $b)
                            <option value="{{ sprintf('%02d', $b) }}" {{ request('bulan') == sprintf('%02d', $b) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-lg-2">
                    <label for="tahun" class="form-label text-muted">Tahun</label>
                    <input type="number" name="tahun" id="tahun" class="form-control" value="{{ request('tahun', date('Y')) }}" min="2020" max="{{ date('Y') + 5 }}">
                </div>

                <div class="col-md-12 col-lg-5 text-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel-fill me-2"></i>Terapkan Filter</button>
                    <a href="{{ route('admin.laporan') }}" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filter</a>
                    {{-- Export to Excel Button --}}
                    <a href="{{ route('admin.laporan.excel', request()->query()) }}" class="btn btn-success"><i class="bi bi-file-earmark-excel-fill me-2"></i>Export ke Excel</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Agenda --}}
    <div class="card shadow border-0">
        <div class="card-body">
            @if($agendas->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">#</th>
                                <th rowspan="2" class="text-center align-middle">Tanggal</th>
                                <th rowspan="2" class="text-center align-middle">Kalas (Kelas)</th>
                                <th colspan="4" class="text-center">Absensi Siswa</th>
                                <th colspan="6" class="text-center">Detail Agenda Mata Pelajaran</th>
                            </tr>
                            <tr>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Izin</th>
                                <th class="text-center">Sakit</th>
                                <th class="text-center">Alpa</th>
                                <th class="text-center">Jam</th>
                                <th class="text-center">Mata Pelajaran</th>
                                <th class="text-center">Guru Pengampu</th>
                                <th class="text-center">Status Guru</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Foto Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agendas as $index => $agenda)
                                @php
                                    $detailRowCount = $agenda->details->count() > 0 ? $agenda->details->count() : 1;
                                @endphp
                                <tr>
                                    <td rowspan="{{ $detailRowCount }}" class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td rowspan="{{ $detailRowCount }}" class="text-nowrap align-middle">{{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td rowspan="{{ $detailRowCount }}" class="align-middle">
                                        <strong>{{ $agenda->user->name ?? '-' }}</strong><br>
                                        {{-- IMPORTANT: Changed to $agenda->kelas->nama_kelas --}}
                                        <span class="text-muted small">{{ $agenda->kelas->nama_kelas ?? '-' }} {{ $agenda->kelas->jurusan ?? '-' }} {{ $agenda->kelas->tingkat ?? '-' }}</span>
                                    </td>
                                    <td rowspan="{{ $detailRowCount }}" class="text-center align-middle">{{ $agenda->jumlah_siswa }}</td>
                                    <td rowspan="{{ $detailRowCount }}" class="text-center align-middle">
                                        @if ($agenda->izin)
                                            <span class="badge bg-warning text-dark">{{ count(explode(',', $agenda->izin)) }}</span>
                                            <br><small class="text-muted">{{ $agenda->izin }}</small>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td rowspan="{{ $detailRowCount }}" class="text-center align-middle">
                                        @if ($agenda->sakit)
                                            <span class="badge bg-info text-dark">{{ count(explode(',', $agenda->sakit)) }}</span>
                                            <br><small class="text-muted">{{ $agenda->sakit }}</small>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    {{-- Alpa column --}}
                                    <td rowspan="{{ $detailRowCount }}" class="text-center align-middle">
                                        @if ($agenda->alpa)
                                            <span class="badge bg-danger">{{ count(explode(',', $agenda->alpa)) }}</span>
                                            <br><small class="text-muted">{{ $agenda->alpa }}</small>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>

                                    {{-- Loop through agenda details for this agenda --}}
                                    @forelse ($agenda->details as $detail)
                                        @if ($loop->first)
                                            <td>{{ \Carbon\Carbon::parse($detail->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($detail->jam_selesai)->format('H:i') }}</td>
                                            <td>{{ $detail->mataPelajaran->nama ?? '-' }}</td>
                                            <td>{{ $detail->guru->nama ?? '-' }}</td>
                                            <td class="text-center">
                                                @if ($detail->status_guru == 'masuk')
                                                    <span class="badge bg-success">Masuk</span>
                                                @elseif ($detail->status_guru == 'izin')
                                                    <span class="badge bg-warning text-dark">Izin</span>
                                                @elseif ($detail->status_guru == 'tidak_hadir')
                                                    <span class="badge bg-danger">Tidak Hadir</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $detail->status_guru)) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $detail->keterangan ?? '-' }}</td>
                                            <td class="text-center">
                                                @if ($detail->foto_kegiatan)
                                                    <a href="{{ asset('storage/' . $detail->foto_kegiatan) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2">
                                                        <i class="bi bi-image"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Tidak ada</span>
                                                @endif
                                            </td>
                                        @else
                                            <tr> {{-- Start a new row for subsequent details --}}
                                                <td>{{ \Carbon\Carbon::parse($detail->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($detail->jam_selesai)->format('H:i') }}</td>
                                                <td>{{ $detail->mataPelajaran->nama ?? '-' }}</td>
                                                <td>{{ $detail->guru->nama ?? '-' }}</td>
                                                <td class="text-center">
                                                    @if ($detail->status_guru == 'masuk')
                                                        <span class="badge bg-success">Masuk</span>
                                                    @elseif ($detail->status_guru == 'izin')
                                                        <span class="badge bg-warning text-dark">Izin</span>
                                                    @elseif ($detail->status_guru == 'tidak_hadir')
                                                        <span class="badge bg-danger">Tidak Hadir</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $detail->status_guru)) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $detail->keterangan ?? '-' }}</td>
                                                <td class="text-center">
                                                    @if ($detail->foto_kegiatan)
                                                        <a href="{{ asset('storage/' . $detail->foto_kegiatan) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2">
                                                            <i class="bi bi-image"></i> Lihat
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">Tidak ada</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        {{-- If no details, fill the detail columns with dashes --}}
                                        <td><span class="text-muted">Tidak ada detail mapel</span></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    @endforelse
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{-- Add pagination if you're paginating $agendas in your controller (e.g., $agendas->links()) --}}
                </div>
            @else
                <div class="alert alert-warning text-center mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Tidak ada data agenda yang ditemukan untuk filter yang dipilih.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
