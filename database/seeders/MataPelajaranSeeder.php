<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $mapels = [
            ['nama' => 'Pemrograman Dasar', 'jurusan' => 'PPLG', 'tingkat' => '10'],
            ['nama' => 'PBO', 'jurusan' => 'PPLG', 'tingkat' => '11'],
            ['nama' => 'Akuntansi Dasar', 'jurusan' => 'AKL', 'tingkat' => '10'],
            ['nama' => 'Perbankan', 'jurusan' => 'AKL', 'tingkat' => '11'],
            ['nama' => 'Manajemen Perkantoran', 'jurusan' => 'MPLB', 'tingkat' => '10'],
            ['nama' => 'Arsip Digital', 'jurusan' => 'MPLB', 'tingkat' => '11'],
            ['nama' => 'Produk Kreatif dan Kewirausahaan', 'jurusan' => 'PM', 'tingkat' => '11'],
            ['nama' => 'Komunikasi Bisnis', 'jurusan' => 'PM', 'tingkat' => '12'],
        ];

        foreach ($mapels as $mp) {
            MataPelajaran::create($mp);
        }
    }
}

