<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('mataPelajarans')->paginate(10);
        return view('guru.index', compact('gurus'));
    }

    public function create()
    {
        $mataPelajarans = MataPelajaran::all();
        return view('guru.create', compact('mataPelajarans'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            // It's good to also add 'nullable' if it's optional,
            // or ensure 'array' truly implies it must be present as an array.
            // For safety, 'sometimes|array' or just handling the null directly is often better.
            'mata_pelajaran_id' => 'array', // This validates it *if present* is an array
            'tingkat' => 'array',
        ]);

        $guru = Guru::create(['nama' => $request->nama]);

        // Get mata_pelajaran_id, providing an empty array as a default if it's null or not present
        $mataPelajaranIds = $request->input('mata_pelajaran_id', []);

        // Loop safely over $mataPelajaranIds
        foreach ($mataPelajaranIds as $mpId) {
            // Get tingkats for the current mpId, providing an empty array as a default
            $tingkats = $request->input('tingkat')[$mpId] ?? []; // Use input() for safety, and null coalescing

            foreach ($tingkats as $tingkat) {
                DB::table('guru_mata_pelajaran')->insert([
                    'guru_id' => $guru->id,
                    'mata_pelajaran_id' => $mpId,
                    'tingkat' => $tingkat,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }


    public function edit(Guru $guru)
    {
        $mataPelajarans = MataPelajaran::all();
        return view('guru.edit', compact('guru', 'mataPelajarans'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'mata_pelajaran_id' => 'array',
            'tingkat' => 'array',
        ]);

        $guru->update(['nama' => $request->nama]);

        // Detach dulu semua mata pelajaran lama agar tidak duplikat
        $guru->mataPelajarans()->detach();

        // Attach ulang per mata pelajaran dan tingkat secara terpisah
        if ($request->has('mata_pelajaran_id')) {
            foreach ($request->mata_pelajaran_id as $mpId) {
                $tingkats = $request->tingkat[$mpId] ?? [];
                foreach ($tingkats as $tingkat) {
                    $guru->mataPelajarans()->attach($mpId, ['tingkat' => $tingkat]);
                }
            }
        }

        return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate.');
    }


    public function destroy(Guru $guru)
    {
        $guru->mataPelajarans()->detach();
        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
