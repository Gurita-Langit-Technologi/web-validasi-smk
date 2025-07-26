<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';

    protected $fillable = ['nama_diklat'];

    public function tugasMengajar()
    {
        return $this->hasMany(TugasMengajar::class, 'id_mapel', 'id_mapel');
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
}
