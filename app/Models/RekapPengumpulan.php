<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapPengumpulan extends Model
{
    protected $table = 'rekap_pengumpulan';
    protected $primaryKey = 'id_tugas';

    protected $fillable = ['id_rekap_kelas', 'id_siswa', 'id_mapel', 'nama_tugas', 'tanggal_pengumpulan', 'keterangan', 'nilai', 'status'];


    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }

    public function rekapKelas()
    {
        return $this->belongsTo(RekapKelas::class, 'id_rekap_kelas');
    }
}
