<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_id',
        'guru_id',           // Stores the ID of the Guru
        'mata_pelajaran_id', // Stores the ID of the MataPelajaran
        'jam_mulai',
        'jam_selesai',
        'status_guru',       // e.g., 'masuk', 'izin', 'tidak_hadir'
        'keterangan',
        'foto_kegiatan',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relationship to the parent Agenda
    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    // Relationship to the Guru model
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relationship to the MataPelajaran model
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
}
