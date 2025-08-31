<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';

    protected $fillable = ['kode_mapel', 'nama_diklat', 'id_guru'];

    public function tugasMengajar()
    {
        return $this->hasMany(TugasMengajar::class, 'id_mapel', 'kode_mapel');
    }

    // Relasi dengan rekap_pengumpulan (1 Mapel bisa memiliki banyak rekap tugas)
    public function rekapPengumpulan()
    {
        return $this->hasMany(RekapPengumpulan::class, 'id_mapel', 'id_mapel');
    }

    public function rekapKelas()
    {
        return $this->hasMany(RekapKelas::class, 'id_mapel');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
