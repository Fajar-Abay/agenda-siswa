<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');  // Nama Kelas
            $table->integer('tingkat');    // Kolom Tingkat (misal: 10, 11, 12)
            $table->string('jurusan');     // Kolom Jurusan (misal: PPLG, AKL, dll)
            $table->integer('kelas');      // Nomor kelas (1, 2, 3, 4)
            $table->integer('jumlah_siswa');  // Jumlah siswa per kelas
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
