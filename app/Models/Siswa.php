<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kelas;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'nisn',
        'nama_siswa',
        'nama_kelas',
        'id_kelas',
        'kompetensi_keahlian',
        'no_induk',

    ];

    public function tugas()
    {
        return $this->hasMany(RekapPengumpulan::class, 'id_siswa');
    }

    public function rekapPengumpulan()
    {
        return $this->hasMany(RekapPengumpulan::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
    // app/Models/Siswa.php



}
