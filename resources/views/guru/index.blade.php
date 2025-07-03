@extends('layouts.admin') {{-- Memastikan Anda menggunakan layout admin yang sama --}}

@section('content')
<div class="container py-4"> {{-- Tambahkan padding vertikal untuk estetika --}}
    <h1 class="mb-4">Daftar Guru</h1> {{-- Margin bawah untuk judul --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"> {{-- Alert yang bisa ditutup --}}
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('guru.create') }}" class="btn btn-primary mb-4"> {{-- Margin bawah untuk tombol --}}
        <i class="fas fa-plus me-2"></i> Tambah Guru {{-- Contoh icon Font Awesome jika digunakan --}}
    </a>

    <div class="table-responsive"> {{-- Membuat tabel responsif untuk layar kecil --}}
        <table class="table table-bordered table-striped table-hover align-middle"> {{-- Styling tabel dasar Bootstrap --}}
            <thead class="table-dark"> {{-- Header tabel gelap --}}
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Guru</th>
                    <th scope="col">Mata Pelajaran & Tingkat</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gurus as $guru) {{-- Gunakan @forelse untuk menangani jika tidak ada data --}}
                    <tr>
                        <td>{{ $loop->iteration + ($gurus->currentPage() - 1) * $gurus->perPage() }}</td>
                        <td>{{ $guru->nama }}</td>
                        <td>
                            @php
                                // Group mata pelajaran berdasarkan id untuk gabungkan tingkat
                                $mapelGrouped = [];
                                foreach ($guru->mataPelajarans as $mp) {
                                    $id = $mp->id;
                                    $tingkat = (string)$mp->pivot->tingkat; // Pastikan ini string
                                    if (!isset($mapelGrouped[$id])) {
                                        $mapelGrouped[$id] = [
                                            'nama' => $mp->nama,
                                            'jurusan' => $mp->jurusan, // Tambahkan jurusan jika ingin ditampilkan
                                            'tingkat' => []
                                        ];
                                    }
                                    if (!in_array($tingkat, $mapelGrouped[$id]['tingkat'])) {
                                        $mapelGrouped[$id]['tingkat'][] = $tingkat;
                                    }
                                }
                            @endphp
                            @if (!empty($mapelGrouped))
                                <ul class="list-unstyled mb-0"> {{-- Hapus styling default ul --}}
                                    @foreach ($mapelGrouped as $mapel)
                                        <li>
                                            <strong>{{ $mapel['nama'] }}</strong> ({{ $mapel['jurusan'] }})
                                            @if (!empty($mapel['tingkat']))
                                                <br>
                                                <small class="text-muted">Tingkat:
                                                @foreach (collect($mapel['tingkat'])->sort() as $tingkat) {{-- Sortir tingkat --}}
                                                    <span class="badge bg-info text-dark me-1">{{ $tingkat }}</span> {{-- Tampilan badge untuk tingkat --}}
                                                @endforeach
                                                </small>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Belum ada mata pelajaran.</span>
                            @endif
                        </td>
                        <td class="text-nowrap"> {{-- Mencegah tombol wrap ke baris baru --}}
                            <a href="{{ route('guru.edit', $guru->id) }}" class="btn btn-warning btn-sm me-2"> {{-- Margin kanan untuk tombol edit --}}
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus guru {{ $guru->nama }}?')"> {{-- Gunakan d-inline --}}
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Tidak ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4"> {{-- Pusatkan pagination --}}
        {{ $gurus->links('pagination::bootstrap-5') }} {{-- Gunakan template pagination Bootstrap 5 --}}
    </div>
</div>
@endsection
