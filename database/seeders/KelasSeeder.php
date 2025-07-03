<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        // Daftar jurusan yang tersedia
        $jurusans = ['PPLG', 'AKL', 'PM', 'MPLB'];

        // Daftar tingkatan yang tersedia
        $tingkat = [10, 11, 12];

        // Nomor kelas (1, 2, 3, 4)
        $kelasNumbers = [1, 2, 3, 4];

        // Jumlah siswa per kelas yang diinputkan manual (bisa disesuaikan)
        $jumlahSiswaPerKelas = [
            'PPLG' => [
                10 => [35, 36, 34, 30],  // Jumlah siswa untuk kelas 10 PPLG
                11 => [32, 33, 34, 31],  // Jumlah siswa untuk kelas 11 PPLG
                12 => [31, 30, 32, 33],  // Jumlah siswa untuk kelas 12 PPLG
            ],
            'AKL' => [
                10 => [30, 29, 28, 32],
                11 => [33, 31, 32, 34],
                12 => [35, 36, 33, 32],
            ],
            'PM' => [
                10 => [38, 40, 36, 35],
                11 => [33, 34, 32, 31],
                12 => [29, 30, 33, 32],
            ],
            'MPLB' => [
                10 => [31, 32, 30, 28],
                11 => [33, 32, 30, 31],
                12 => [35, 34, 32, 33],
            ],
        ];

        // Looping untuk setiap kombinasi jurusan, tingkat dan kelas
        foreach ($jurusans as $jurusan) {
            foreach ($tingkat as $tingkatVal) {
                foreach ($kelasNumbers as $kelasNo) {
                    // Ambil jumlah siswa untuk kombinasi jurusan, tingkat, dan kelas
                    $jumlahSiswa = $jumlahSiswaPerKelas[$jurusan][$tingkatVal][$kelasNo - 1]; // Index mulai dari 0

                    // Membuat kelas dengan jumlah siswa yang sudah ditentukan
                    Kelas::create([
                        'nama_kelas' => "{$jurusan} {$tingkatVal} - Kelas {$kelasNo}",
                        'tingkat' => $tingkatVal,
                        'jurusan' => $jurusan,
                        'kelas' => $kelasNo,
                        'jumlah_siswa' => $jumlahSiswa, // Menggunakan jumlah siswa yang diinputkan manual
                    ]);
                }
            }
        }
    }
}
