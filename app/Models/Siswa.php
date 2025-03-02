<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = ['nisn', 'nama_siswa', 'kode_kelas', 'nama_kelas', 'jurusan'];

    public function tugas()
    {
        return $this->hasMany(RekapPengumpulan::class, 'id_siswa');
    }

    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class, 'Kelas');
    // }
}
