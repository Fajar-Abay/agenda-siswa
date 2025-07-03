<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaDetail;
use App\Models\Guru;
use Illuminate\Http\Request;

class AgendaDetailController extends Controller
{
   public function create(Agenda $agenda)
    {
        $jurusan = auth()->user()->kelas->jurusan;
        $tingkat = auth()->user()->kelas->tingkat;

        $gurus = Guru::whereHas('mataPelajarans', function ($q) use ($jurusan, $tingkat) {
            $q->where('jurusan', $jurusan)
            ->where('guru_mata_pelajaran.tingkat', $tingkat);  // tabel dipanggil lengkap
        })->with(['mataPelajarans' => function ($q) use ($jurusan, $tingkat) {
            $q->where('jurusan', $jurusan)
            ->where('guru_mata_pelajaran.tingkat', $tingkat);  // tabel dipanggil lengkap
        }])->get();

        return view('agenda.detail_create', compact('agenda', 'gurus'));
    }



    public function store(Request $request, Agenda $agenda)
    {
        $request->validate([
            'guru_id'           => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'jam_mulai'         => 'required|date_format:H:i',
            'jam_selesai'       => 'required|date_format:H:i|after:jam_mulai',
            'status_guru'       => 'required|in:masuk,izin,tidak_hadir',
            'keterangan'        => 'nullable|string|max:1000',
            'foto_kegiatan'     => 'required|image|max:2048', // Max 2MB
        ]);

        $path = $request->file('foto_kegiatan')->store('agenda_photos', 'public');

        $agenda->details()->create([
            'guru_id'           => $request->guru_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'status_guru'       => $request->status_guru,
            'keterangan'        => $request->keterangan,
            'foto_kegiatan'     => $path,
            'agenda_id'         => $agenda->id,
        ]);

        return redirect()->route('agenda.details.create', $agenda->id)
                         ->with('success', 'Mata pelajaran berhasil ditambahkan ke agenda!');
    }
}
