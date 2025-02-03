<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';

    protected $fillable = ['nama_mapel', 'tema_tugas', 'total_tugas', 'jumlah_selesai', 'jumlah_tanggungan', 'deskripsi'];

    public function rekapKelas()
    {
        return $this->hasMany(RekapKelas::class, 'id_mapel');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_mapel');
    }
}
