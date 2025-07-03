@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Mata Pelajaran: {{ $singleMataPelajaran->nama }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mapel.update', $singleMataPelajaran->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Mata Pelajaran</label>
            <input type="text" name="nama" id="nama" class="form-control"
                   value="{{ old('nama', $singleMataPelajaran->nama) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Jurusan</label>
            <div class="row">
                {{-- Anda bisa ambil daftar jurusan ini dari database jika ada tabel jurusans --}}
                @php
                    $allJurusanOptions = ['PPLG', 'MPLB', 'AKL', 'PM'];
                @endphp
                @foreach($allJurusanOptions as $jurusanOption)
                    <div class="col-auto mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="jurusan[]"
                                   value="{{ $jurusanOption }}" id="jurusan-{{ Str::slug($jurusanOption) }}"
                                   {{ (is_array(old('jurusan')) ? in_array($jurusanOption, old('jurusan')) : in_array($jurusanOption, $selectedJurusans)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="jurusan-{{ Str::slug($jurusanOption) }}">
                                {{ $jurusanOption }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('jurusan')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Tingkat</label>
            <div class="row">
                @foreach(['10', '11', '12'] as $tingkatOption)
                    <div class="col-auto mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tingkat[]"
                                   value="{{ $tingkatOption }}" id="tingkat-{{ $tingkatOption }}"
                                   {{ (is_array(old('tingkat')) ? in_array($tingkatOption, old('tingkat')) : in_array($tingkatOption, $selectedTingkats)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tingkat-{{ $tingkatOption }}">
                                {{ $tingkatOption }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('tingkat')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">Update</button>
            <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
