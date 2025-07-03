<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar jurusan
        $jurusans = ['PPLG', 'AKL', 'PM', 'MPLB'];

        // Daftar tingkatan yang tersedia
        $tingkat = [10, 11, 12];

        // Nomor kelas (1, 2, 3, 4)
        $kelasNumbers = [1, 2, 3, 4];

        // Menambahkan admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Loop untuk membuat user berdasarkan kombinasi jurusan, tingkat, dan kelas
        foreach ($jurusans as $jurusan) {
            foreach ($tingkat as $t) {
                foreach ($kelasNumbers as $k) {
                    $email = strtolower("{$t}{$jurusan}{$k}@example.com"); // Email unik untuk setiap kombinasi
                    $name = "Kelas {$t} {$jurusan} {$k}"; // Nama user berdasarkan jurusan, tingkat, dan kelas

                    // Password sesuai dengan format tingkat, jurusan dan kelas
                    $password = "{$t} {$jurusan} {$k}-123"; // Contoh: XI PPLG 1-123

                    // Menyimpan user ke database
                    User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password), // Password yang dihash
                        'role' => 'kalas', // Asumsi user adalah kalas
                        'kelas_id' => $this->getKelasId($jurusan, $t, $k), // Menyimpan kelas sesuai dengan jurusan, tingkat dan kelas
                    ]);
                }
            }
        }
    }

    /**
     * Menentukan ID kelas berdasarkan jurusan, tingkat, dan kelas.
     */
    private function getKelasId($jurusan, $tingkat, $kelas)
    {
        // Cari kelas berdasarkan jurusan, tingkat, dan kelas
        $kelas = \App\Models\Kelas::where('jurusan', $jurusan)
                                  ->where('tingkat', $tingkat)
                                  ->where('kelas', $kelas)
                                  ->first();

        return $kelas ? $kelas->id : null; // Jika kelas ditemukan, kembalikan ID kelas, jika tidak return null
    }
}
