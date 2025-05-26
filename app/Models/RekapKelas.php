<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapKelas extends Model
{
    protected $table = 'rekap_kelas';
    protected $primaryKey = 'id_rekap_kelas';

    protected $fillable = ['id_kelas', 'id_mapel', 'id_guru', 'total_tugas', 'jumlah_selesai', 'jumlah_tanggungan',];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }

    public function tugas()
    {
        return $this->hasMany(RekapPengumpulan::class, 'id_rekap_kelas', 'id_rekap_kelas')->distinct();
    }

    public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }
}
