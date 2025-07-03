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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');

            // Relasi ke tabel kelas
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');

            $table->unsignedInteger('jumlah_siswa'); // ini untuk siswa hadir (otomatis dihitung)
            $table->text('izin')->nullable();        // input manual nama siswa
            $table->text('sakit')->nullable();       // input manual nama siswa
            $table->text('alpa')->nullable();        // input manual nama siswa

            $table->foreignId('created_by')->constrained('users'); // relasi ke user yang membuat agenda
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
