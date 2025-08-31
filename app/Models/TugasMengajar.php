<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;

class TugasMengajar extends Model
{
    protected $table = 'tugas_mengajar';
    protected $primaryKey = 'id_mengajar';
    protected $fillable = [
        'kode_guru',
        // 'nama_guru',
        'kode_kelas',
        // 'mata_diklat',
        // 'kompetensi_keahlian',
        // 'id_guru',
        // 'id_kelas',
        'kode_mapel',
        // 'kode_guru',
        // 'nama_guru'
    ];

    // App\Models\TugasMengajar.php
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'kode_guru', 'kode_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kode_kelas', 'kode_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'kode_mapel', 'kode_mapel');
    }
}
