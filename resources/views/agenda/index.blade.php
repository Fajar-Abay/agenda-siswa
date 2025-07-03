@extends('layouts.users')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Agenda Hari Ini</h2>

    @if(!$agenda)
        <div class="alert alert-info">
            Anda belum mengisi agenda hari ini (<strong>{{ now()->format('d M Y') }}</strong>).
            <a href="{{ route('agenda.create') }}" class="btn btn-sm btn-primary ms-3">Isi Agenda</a>
        </div>
    @else
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ auth()->user()->name }} - {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }}</h5>
                <p class="mb-1"><strong>Jumlah Total:</strong> {{ $agenda->kelas->jumlah_siswa }}</p>
                <p class="mb-1"><strong>Hadir:</strong> {{ $agenda->jumlah_siswa }}</p>
                <p class="mb-1"><strong>Izin:</strong> {{ $agenda->izin ?: '-' }}</p>
                <p class="mb-1"><strong>Sakit:</strong> {{ $agenda->sakit ?: '-' }}</p>
                <p class="mb-1"><strong>Alpa:</strong> {{ $agenda->alpa ?: '-' }}</p>
                <a href="{{ route('agenda.details.create', $agenda->id) }}" class="btn btn-success mt-3">Tambah Mapel</a>
            </div>
        </div>

        @if($agenda->details->count())
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Detail Mapel</h5>
                    <ul class="list-group">
                        @foreach($agenda->details as $detail)
                           <li class="list-group-item d-flex align-items-center justify-content-between">
                            <div>
                                <strong>{{ \Carbon\Carbon::parse($detail->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($detail->jam_selesai)->format('H:i') }}</strong><br>
                                {{-- Access guru and mata pelajaran names through relationships --}}
                                <span>{{ $detail->mataPelajaran->nama ?? 'N/A' }} ({{ $detail->guru->nama ?? 'N/A' }})</span><br>
                                <small><strong>Status Guru:</strong> {{ ucfirst(str_replace('_', ' ', $detail->status_guru)) }}</small>
                                @if($detail->keterangan)
                                    <br><small><strong>Keterangan:</strong> {{ $detail->keterangan }}</small>
                                @endif
                            </div>
                            <div>
                                <img src="{{ asset('storage/' . $detail->foto_kegiatan) }}" alt="Foto Kegiatan" class="img-fluid rounded" style="max-width: 80px; height: auto; object-fit: cover;">
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div class="alert alert-info mt-4">Belum ada detail mata pelajaran untuk agenda ini.</div>
        @endif
    @endif
</div>
@endsection
