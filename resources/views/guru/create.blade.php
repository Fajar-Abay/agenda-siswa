@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Tambah Guru</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Guru</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <h5>Mata Pelajaran & Tingkat yang Diajar</h5>

        @foreach ($mataPelajarans as $mp)
            <div class="mb-3 border p-2 rounded">
                <label>
                    <input type="checkbox" class="mp-checkbox" name="mata_pelajaran_id[]" value="{{ $mp->id }}" id="mp{{ $mp->id }}"
                    {{ (is_array(old('mata_pelajaran_id')) && in_array($mp->id, old('mata_pelajaran_id'))) ? 'checked' : '' }}>
                    {{ $mp->nama }} ({{ $mp->jurusan }})
                </label>

                <div class="tingkat-checkboxes ms-4 mt-2" style="display: {{ (is_array(old('mata_pelajaran_id')) && in_array($mp->id, old('mata_pelajaran_id'))) ? 'block' : 'none' }}">
                    <label><input type="checkbox" name="tingkat[{{ $mp->id }}][]" value="10"
                        {{ collect(old("tingkat.$mp->id") ?? [])->contains('10') ? 'checked' : '' }}>Kelas 10</label>
                    <label><input type="checkbox" name="tingkat[{{ $mp->id }}][]" value="11"
                        {{ collect(old("tingkat.$mp->id") ?? [])->contains('11') ? 'checked' : '' }}> 11</label>
                    <label><input type="checkbox" name="tingkat[{{ $mp->id }}][]" value="12"
                        {{ collect(old("tingkat.$mp->id") ?? [])->contains('12') ? 'checked' : '' }}> 12</label>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success">Simpan</button>
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
</script>
@endsection
