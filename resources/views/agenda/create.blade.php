@extends('layouts.users')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Isi Agenda Hari Ini</h2>
    <form action="{{ route('agenda.store') }}" method="POST">@csrf
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
        </div>

        @if(auth()->user()->kelas) <!-- Cek apakah user memiliki kelas -->
        <div class="mb-3">
            <label class="form-label">Kelas Anda</label>
            <input type="text" class="form-control" value="{{ auth()->user()->kelas->tingkat }} {{ auth()->user()->kelas->jurusan }} {{ auth()->user()->kelas->rombel }}" disabled>
            <input type="hidden" name="kelas_id" value="{{ auth()->user()->kelas->id }}"> <!-- Menyimpan ID kelas -->
        </div>
        @else
        <div class="alert alert-warning">
            Anda tidak memiliki kelas yang terkait. Harap hubungi administrator.
        </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Izin (pisahkan koma)</label>
            <textarea name="izin" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Sakit (pisahkan koma)</label>
            <textarea name="sakit" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Alpa (pisahkan koma)</label>
            <textarea name="alpa" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan Agenda</button>
    </form>
</div>
@endsection
