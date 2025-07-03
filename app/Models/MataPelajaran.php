<?php

// app/Models/MataPelajaran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $fillable = ['nama', 'jurusan',"tingkat"];

    // nama plural juga untuk konsisten
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mata_pelajaran')
                    ->withPivot('tingkat')
                    ->withTimestamps();
    }
}
