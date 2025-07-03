<?php

// app/Models/Guru.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = ['nama'];

    // gunakan nama plural agar konsisten dengan view
    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mata_pelajaran')
                    ->withPivot('tingkat')
                    ->withTimestamps();
    }
    public function getMapelGroupedAttribute()
    {
        // Mengelompokkan mata pelajaran dan decode tingkat dari pivot
        return $this->mataPelajarans->map(function ($mapel) {
            return [
                'nama' => $mapel->nama,
                'tingkat' => json_decode($mapel->pivot->tingkat) ?: [],
            ];
        });
    }
}
