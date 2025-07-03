<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran; // Import model MataPelajaran
use Illuminate\Http\Request;
use App\Models\Guru;
use Illuminate\Support\Facades\DB;


class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua mata pelajaran.
     */
   public function index()
    {
        // Ambil semua mata pelajaran dan kelompokkan berdasarkan nama
        $allMataPelajarans = MataPelajaran::all();

        $groupedMataPelajarans = $allMataPelajarans->groupBy('nama')->map(function ($items) {
            // $items adalah koleksi MataPelajaran untuk nama yang sama (misal: semua Karawitan)
            return [
                'id' => $items->first()->id, // Ambil ID dari salah satu entri untuk link edit/hapus
                'nama' => $items->first()->nama,
                'jurusans' => $items->pluck('jurusan')->unique()->sort()->implode(', '),
                'tingkats' => $items->pluck('tingkat')->unique()->sort()->implode(', '),
            ];
        })->values(); // Mengubah koleksi menjadi indexed array

        return view('mata_pelajaran.index', compact('groupedMataPelajarans'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat mata pelajaran baru.
     */
    public function create()
    {
        // Kirim ke view form tambah
        return view('mata_pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan mata pelajaran baru ke database.
     */
    public function store(Request $request){
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|array|min:1', // Jurusan harus berupa array
            'jurusan.*' => 'string|max:255',     // Setiap item jurusan adalah string
            'tingkat' => 'required|array|min:1', // Tingkat juga harus berupa array dan tidak boleh kosong
            'tingkat.*' => 'in:10,11,12',        // Setiap item tingkat harus 10, 11, atau 12
        ]);

        $namaMataPelajaran = $request->input('nama');
        $selectedJurusans = $request->input('jurusan');
        $selectedTingkats = $request->input('tingkat'); // Sekarang ini juga array

        DB::beginTransaction(); // Mulai transaksi database
        try {
            foreach ($selectedJurusans as $jurusan) {
                foreach ($selectedTingkats as $tingkat) { // Loop kedua untuk setiap tingkat
                    // Periksa apakah kombinasi nama, jurusan, tingkat sudah ada
                    $existingMapel = MataPelajaran::where('nama', $namaMataPelajaran)
                                                    ->where('jurusan', $jurusan)
                                                    ->where('tingkat', $tingkat)
                                                    ->first();

                    if ($existingMapel) {
                        // Jika sudah ada, lewati dan lanjutkan ke kombinasi berikutnya
                        continue;
                    }

                    // Buat record baru di database untuk setiap kombinasi nama-jurusan-tingkat
                    MataPelajaran::create([
                        'nama' => $namaMataPelajaran,
                        'jurusan' => $jurusan,
                        'tingkat' => $tingkat,
                    ]);
                }
            }

            DB::commit(); // Commit transaksi jika semua berhasil
            return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan untuk semua kombinasi yang dipilih.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi error
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menambahkan mata pelajaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit mata pelajaran yang sudah ada.
     */

    public function edit(string $id)
    {
        // Pastikan baris ini ada dan benar
        $singleMataPelajaran = MataPelajaran::findOrFail($id);

        // Ambil data terkait lainnya
        $relatedMataPelajarans = MataPelajaran::where('nama', $singleMataPelajaran->nama)->get();
        $selectedJurusans = $relatedMataPelajarans->pluck('jurusan')->unique()->toArray();
        $selectedTingkats = $relatedMataPelajarans->pluck('tingkat')->unique()->toArray();
        $allJurusanOptions = ['PPLG', 'MPLB', 'AKL', 'PM']; // Pastikan ini juga didefinisikan atau diambil dari DB

        // Pastikan 'singleMataPelajaran' ada di compact() atau array yang dikirim
        return view('mata_pelajaran.edit', compact(
            'singleMataPelajaran',
            'selectedJurusans',
            'selectedTingkats',
            'allJurusanOptions'
        ));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data mata pelajaran di database.
     */

     public function update(Request $request, string $id)
    {
        // 1. Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|array|min:1', // Harus array dan minimal 1
            'jurusan.*' => 'string|max:255',
            'tingkat' => 'required|array|min:1', // Harus array dan minimal 1
            'tingkat.*' => 'in:10,11,12',
        ]);

        // Ambil data mata pelajaran yang sedang diedit (dari ID di URL)
        $currentMataPelajaran = MataPelajaran::findOrFail($id);

        $newNamaMataPelajaran = $request->input('nama');
        $newSelectedJurusans = $request->input('jurusan');
        $newSelectedTingkats = $request->input('tingkat');

        DB::beginTransaction(); // Mulai transaksi database
        try {
            // 2. Identifikasi dan Hapus Entri Lama
            // Kita akan menghapus semua entri yang memiliki NAMA MATA PELAJARAN LAMA
            // (sebelum diubah) atau NAMA MATA PELAJARAN BARU (jika nama diubah)
            // yang terkait dengan konsep mata pelajaran yang sedang diedit ini.
            // Ini untuk memastikan semua kombinasi lama terhapus sebelum membuat yang baru.

            // Ambil semua ID mata pelajaran yang memiliki nama yang sama dengan nama LAMA atau nama BARU
            // Ini penting jika nama mata pelajaran diizinkan berubah, agar semua record terkait terproses.
            // Jika nama mata pelajaran TIDAK diizinkan berubah, cukup pakai $currentMataPelajaran->nama.
            MataPelajaran::where('nama', $currentMataPelajaran->nama)
                         ->orWhere('nama', $newNamaMataPelajaran) // Pertimbangkan jika nama di form berbeda
                         ->delete();


            // 3. Buat Entri Baru Berdasarkan Input Form
            foreach ($newSelectedJurusans as $jurusan) {
                foreach ($newSelectedTingkats as $tingkat) {
                    // Periksa duplikasi sebelum membuat (penting jika Anda tidak punya unique constraint di DB)
                    // Meskipun sudah dihapus di atas, ini adalah safeguard tambahan.
                    $existingMapel = MataPelajaran::where('nama', $newNamaMataPelajaran)
                                                    ->where('jurusan', $jurusan)
                                                    ->where('tingkat', $tingkat)
                                                    ->first();

                    if (!$existingMapel) { // Hanya buat jika belum ada
                        MataPelajaran::create([
                            'nama' => $newNamaMataPelajaran,
                            'jurusan' => $jurusan,
                            'tingkat' => $tingkat,
                        ]);
                    }
                }
            }

            DB::commit(); // Commit transaksi jika semua berhasil
            return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui untuk semua kombinasi yang dipilih.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi error
            // Log error atau tampilkan pesan kesalahan
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui mata pelajaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus mata pelajaran dari database.
     */
    public function destroy(string $id)
        {
            // Temukan SATU entri mata pelajaran berdasarkan ID yang diterima dari URL.
            // ID ini adalah ID dari salah satu kombinasi yang ada di database.
            $mataPelajaran = MataPelajaran::findOrFail($id);

            // Dapatkan NAMA mata pelajaran dari entri yang ditemukan.
            // Ini adalah kunci untuk menghapus semua kombinasinya.
            $namaToDelete = $mataPelajaran->nama;

            // Hapus SEMUA entri di tabel mata_pelajarans yang memiliki nama yang sama.
            MataPelajaran::where('nama', $namaToDelete)->delete();

            // Redirect kembali ke halaman indeks dengan pesan sukses.
            return redirect()->route('mapel.index')->with('success', "Mata pelajaran '{$namaToDelete}' dan semua kombinasinya berhasil dihapus.");
        }

}
