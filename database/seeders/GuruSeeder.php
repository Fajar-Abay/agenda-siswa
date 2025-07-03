<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\MataPelajaran;

class GuruSeeder extends Seeder
{
    public function run()
    {
        // Tambahkan guru
        $guru1 = Guru::create(['nama' => 'Rudi']);
        $guru2 = Guru::create(['nama' => 'Santi']);
        $guru3 = Guru::create(['nama' => 'Dewi']);
        $guru4 = Guru::create(['nama' => 'Anto']);

        // Ambil mapel berdasarkan jurusan
        $mapelPPLG = MataPelajaran::where('jurusan', 'PPLG')->get();
        $mapelAKL  = MataPelajaran::where('jurusan', 'AKL')->get();
        $mapelMPLB = MataPelajaran::where('jurusan', 'MPLB')->get();
        $mapelPM   = MataPelajaran::where('jurusan', 'PM')->get();

        // Attach mapel ke guru dengan informasi tingkat
        foreach ($mapelPPLG as $mapel) {
            $guru1->mataPelajarans()->attach($mapel->id, ['tingkat' => $mapel->tingkat]);
        }

        foreach ($mapelAKL as $mapel) {
            $guru2->mataPelajarans()->attach($mapel->id, ['tingkat' => $mapel->tingkat]);
        }

        foreach ($mapelMPLB as $mapel) {
            $guru3->mataPelajarans()->attach($mapel->id, ['tingkat' => $mapel->tingkat]);
        }

        foreach ($mapelPM as $mapel) {
            $guru4->mataPelajarans()->attach($mapel->id, ['tingkat' => $mapel->tingkat]);
        }
    }
}
