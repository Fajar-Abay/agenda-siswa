@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Tambah Mata Pelajaran</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mapel.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Mata Pelajaran</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Jurusan</label> {{-- d-block agar label di atas --}}
            <div class="row"> {{-- Gunakan row untuk tata letak horizontal --}}
                {{-- Contoh data jurusan, Anda mungkin perlu mengambilnya dari database Jurusan::all() jika ada --}}
                @php
                    // Pastikan Anda memiliki daftar jurusan ini, bisa dari controller atau hardcode seperti ini
                    $allJurusanOptions = ['PPLG', 'MPLB', 'AKL', 'PM'];
                @endphp
                @foreach($allJurusanOptions as $jurusanOption)
                    <div class="col-auto mb-2"> {{-- col-auto agar kolom menyesuaikan konten, mb-2 untuk margin bawah --}}
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="jurusan[]"
                                   value="{{ $jurusanOption }}" id="jurusan-{{ Str::slug($jurusanOption) }}"
                                   {{ (is_array(old('jurusan')) && in_array($jurusanOption, old('jurusan'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="jurusan-{{ Str::slug($jurusanOption) }}">
                                {{ $jurusanOption }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('jurusan') {{-- Tampilkan error validasi di bawah grup checkbox --}}
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Tingkat</label> {{-- d-block agar label di atas --}}
            <div class="row"> {{-- Gunakan row untuk tata letak horizontal --}}
                @foreach(['10', '11', '12'] as $tingkatOption)
                    <div class="col-auto mb-2"> {{-- col-auto agar kolom menyesuaikan konten, mb-2 untuk margin bawah --}}
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tingkat[]"
                                   value="{{ $tingkatOption }}" id="tingkat-{{ $tingkatOption }}"
                                   {{ (is_array(old('tingkat')) && in_array($tingkatOption, old('tingkat'))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tingkat-{{ $tingkatOption }}">
                                {{ $tingkatOption }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('tingkat') {{-- Tampilkan error validasi di bawah grup checkbox --}}
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success me-2">Simpan</button>
            <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
