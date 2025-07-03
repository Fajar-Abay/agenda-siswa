@extends('layouts.users')

@section('content')
<div class="container ms-4">
    <h1 class="mb-4 text-center">Laporan Agenda</h1>

    <!-- Form Filter Tanggal -->
    <div class="d-flex justify-content-between mb-3">
        <form method="GET" action="{{ route('agenda.laporan') }}" class="d-flex">
            <input type="date" name="tanggal_mulai" class="form-control me-2" value="{{ request('tanggal_mulai') }}" />
            <input type="date" name="tanggal_selesai" class="form-control me-2" value="{{ request('tanggal_selesai') }}" />
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <!-- Tabel Laporan -->
    <div class="card">
        <div class="card-header">
            Laporan Agenda yang Dibuat
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Jumlah Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alpa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($agendas->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada laporan untuk periode yang dipilih.</td>
                            </tr>
                        @else
                            @foreach ($agendas as $agenda)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($agenda->tanggal)->toDateString() }}</td>
                                    <td>{{ $agenda->kelas->nama_kelas }} ({{ $agenda->kelas->jumlah_siswa }} Siswa)</td>
                                    <td>{{ $agenda->jumlah_siswa }}</td>
                                    <td>{{ $agenda->izin ?: 'Tidak ada data' }}</td>
                                    <td>{{ $agenda->sakit ?: 'Tidak ada data' }}</td>
                                    <td>{{ $agenda->alpa ?: 'Tidak ada data' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Button untuk download laporan Excel dengan filter tanggal -->
    <div class="mt-4 text-center">
        <a href="{{ route('agenda.laporan.excel', [
            'tanggal_mulai' => request('tanggal_mulai'),
            'tanggal_selesai' => request('tanggal_selesai'),
        ]) }}" class="btn btn-success">Download Laporan Excel</a>
    </div>
</div>
@endsection
