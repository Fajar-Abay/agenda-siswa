<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jumlah_siswa',
        "kelas_id",
        'izin',
        'sakit',
        'alpa',
        'created_by',
    ];

    protected $dates = ['tanggal'];

     protected $casts = [
        'tanggal' => 'date',  // <-- ini yang bikin $agenda->tanggal jadi Carbon instance
    ];

    // Relasi ke detail agenda (per mapel)
    public function details()
    {
        return $this->hasMany(AgendaDetail::class);
    }

    // Relasi ke user yang membuat
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
     public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}

