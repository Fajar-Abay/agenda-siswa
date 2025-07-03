@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Daftar Mata Pelajaran</h1>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('mapel.create') }}" class="btn btn-primary">Tambah Mata Pelajaran</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Jurusan</th>
                    <th>Tingkat</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groupedMataPelajarans as $key => $mapel)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $mapel['nama'] }}</td>
                        <td>{{ $mapel['jurusans'] }}</td>
                        <td>{{ $mapel['tingkats'] }}</td>
                        <td>
                            {{-- ID yang digunakan di sini adalah ID dari salah satu kombinasi,
                                 yang akan digunakan oleh metode edit/destroy untuk mendapatkan nama mapel --}}
                            <a href="{{ route('mapel.edit', $mapel['id']) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('mapel.destroy', $mapel['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran {{ $mapel['nama'] }} dan semua kombinasinya?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada mata pelajaran yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
