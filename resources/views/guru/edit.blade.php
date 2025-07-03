@extends('layouts.admin') {{-- Pastikan layout ini benar, sebelumnya 'layouts.edit' --}}

@section('content')
<div class="container">
    <h1>Edit Guru: {{ $guru->nama }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.update', $guru->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Guru</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $guru->nama) }}" required>
        </div>

        <h5>Mata Pelajaran & Tingkat yang Diajar</h5>

        @php
            // Kumpulkan semua mata pelajaran dan tingkat yang diajar guru ini dari database.
            // Format: [mp_id => [tingkat1, tingkat2, ...]]
            $guruMataPelajaranTingkat = [];
            foreach ($guru->mataPelajarans as $currentMp) {
                if (!isset($guruMataPelajaranTingkat[$currentMp->id])) {
                    $guruMataPelajaranTingkat[$currentMp->id] = [];
                }
                // Pastikan tingkat ditambahkan ke array
                $guruMataPelajaranTingkat[$currentMp->id][] = (string)$currentMp->pivot->tingkat;
            }
        @endphp

        @foreach ($mataPelajarans as $mp)
            @php
                // Ambil nilai 'old' untuk tingkat jika ada, pastikan selalu array
                $tingkatFromOld = old("tingkat.{$mp->id}") ?? [];
                if (!is_array($tingkatFromOld)) {
                    $tingkatFromOld = [];
                }

                // Ambil data tingkat dari database untuk mata pelajaran ini
                $tingkatFromDb = $guruMataPelajaranTingkat[$mp->id] ?? [];

                // Tentukan tingkat yang seharusnya dicentang: prioritaskan old input
                $selectedTingkatForMp = !empty($tingkatFromOld) ? $tingkatFromOld : $tingkatFromDb;

                // Tentukan apakah checkbox mata pelajaran harus dicentang
                // Checkbox dicentang jika ada old input untuk mata pelajaran ini,
                // ATAU jika mata pelajaran ini punya tingkat yang diajar di DB
                $selectedMataPelajaranCheck = (old('mata_pelajaran_id') && in_array($mp->id, old('mata_pelajaran_id'))) || !empty($tingkatFromDb);
            @endphp

            <div class="mb-3 border p-2 rounded">
                <label>
                    <input type="checkbox" class="mp-checkbox" name="mata_pelajaran_id[]" value="{{ $mp->id }}" id="mp{{ $mp->id }}"
                    {{ $selectedMataPelajaranCheck ? 'checked' : '' }}>
                    {{ $mp->nama }} ({{ $mp->jurusan }})
                </label>

                {{-- Kontainer untuk checkbox tingkat, defaultnya disembunyikan jika mata pelajaran tidak dipilih --}}
                <div class="tingkat-checkboxes ms-4 mt-2" style="display: {{ $selectedMataPelajaranCheck ? 'block' : 'none' }}">
                    <label>
                        <input type="checkbox" name="tingkat[{{ $mp->id }}][]" value="10"
                            {{ in_array('10', $selectedTingkatForMp) ? 'checked' : '' }}> 10
                    </label>
                    <label class="ms-3"> {{-- Tambahkan margin untuk jarak antar checkbox --}}
                        <input type="checkbox" name="tingkat[{{ $mp->id }}][]" value="11"
                            {{ in_array('11', $selectedTingkatForMp) ? 'checked' : '' }}> 11
                    </label>
                    <label class="ms-3">
                        <input type="checkbox" name="tingkat[{{ $mp->id }}][]" value="12"
                            {{ in_array('12', $selectedTingkatForMp) ? 'checked' : '' }}> 12
                    </label>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    // Show/hide tingkat checkboxes saat checkbox mata pelajaran dicentang/diuncheck
    document.querySelectorAll('.mp-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const tingkatDiv = this.closest('div').querySelector('.tingkat-checkboxes');
            if (this.checked) {
                tingkatDiv.style.display = 'block';
            } else {
                tingkatDiv.style.display = 'none';
                // Deselect semua tingkat checkbox jika mata pelajaran tidak dicentang
                tingkatDiv.querySelectorAll('input[type="checkbox"]').forEach(input => input.checked = false);
            }
        });
    });

    // Panggil event change secara manual saat halaman dimuat
    // agar status display pada tingkat checkboxes sesuai dengan checkbox mata pelajaran saat pertama kali load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.mp-checkbox').forEach(cb => {
            const tingkatDiv = cb.closest('div').querySelector('.tingkat-checkboxes');
            if (cb.checked) {
                tingkatDiv.style.display = 'block';
            } else {
                tingkatDiv.style.display = 'none';
            }
        });
    });
</script>
@endsection
