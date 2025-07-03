@extends('layouts.users')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Tambah Mapel — {{ \Carbon\Carbon::parse($agenda->tanggal)->format('d M Y') }}</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('agenda.details.store', $agenda->id) }}" method="POST" enctype="multipart/form-data" class="card shadow-sm p-4">
        @csrf

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="guruSelect" class="form-label">Guru Pengajar <span class="text-danger">*</span></label>
                <select id="guruSelect" name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required>
                    <option value="">Pilih Guru</option>
                    @foreach ($gurus as $guru)
                        {{-- Store relevant mata pelajaran data as a JSON string --}}
                        <option value="{{ $guru->id }}"
                                data-mapels="{{ $guru->mataPelajarans->toJson() }}"
                                {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama }}
                        </option>
                    @endforeach
                </select>
                @error('guru_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="mapelSelect" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                <select id="mapelSelect" name="mata_pelajaran_id" class="form-select @error('mata_pelajaran_id') is-invalid @enderror" required>
                    <option value="">Pilih Mapel</option>
                    {{-- Options will be populated by JavaScript based on selected guru --}}
                </select>
                @error('mata_pelajaran_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="jam_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}" required>
                @error('jam_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="jam_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}" required>
                @error('jam_selesai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="foto_kegiatan" class="form-label">Foto Kegiatan <span class="text-danger">*</span></label>
            <input type="file" name="foto_kegiatan" id="foto_kegiatan" class="form-control @error('foto_kegiatan') is-invalid @enderror" accept="image/*" required>
            @error('foto_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="status_guru" class="form-label">Status Guru <span class="text-danger">*</span></label>
            <select name="status_guru" id="status_guru" class="form-select @error('status_guru') is-invalid @enderror" required>
                <option value="masuk" {{ old('status_guru') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="izin" {{ old('status_guru') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="tidak_hadir" {{ old('status_guru') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
            </select>
            @error('status_guru')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('agenda.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-2"></i>Selesai</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Mapel</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const guruSelect = document.getElementById('guruSelect');
        const mapelSelect = document.getElementById('mapelSelect');

        function populateMapelSelect(selectedGuruOption) {
            mapelSelect.innerHTML = '<option value="">Pilih Mapel</option>';
            const mapelsData = selectedGuruOption ? JSON.parse(selectedGuruOption.getAttribute('data-mapels')) : [];

            mapelsData.forEach(function (mapel) {
                const option = document.createElement('option');
                option.value = mapel.id; // Use mapel ID as value
                option.text = mapel.nama + ' (' + mapel.jurusan + ' Kelas ' + mapel.pivot.tingkat + ')'; // Display name, jurusan, and tingkat
                mapelSelect.appendChild(option);
            });

            // If there's an old value for mata_pelajaran_id, try to re-select it
            const oldMapelId = "{{ old('mata_pelajaran_id') }}";
            if (oldMapelId) {
                mapelSelect.value = oldMapelId;
            }
        }

        // Initial population if a guru was old-selected
        if (guruSelect.value) {
            const selectedOption = guruSelect.options[guruSelect.selectedIndex];
            populateMapelSelect(selectedOption);
        }

        // Event listener for guru selection change
        guruSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            populateMapelSelect(selectedOption);
        });
    });
</script>
@endsection
