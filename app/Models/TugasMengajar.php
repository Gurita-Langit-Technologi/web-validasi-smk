<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasMengajar extends Model
{
    protected $table = 'tugas_mengajar';
    protected $primaryKey = 'id_mengajar';
    protected $fillable = [
        'kode_guru',
        'nama_guru',
        'kelas',
        'mata_diklat',
        'jurusan'
    ];

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'kode_guru');
    }

    // Relasi ke Mata Pelajaran
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'kode_kelas');
    }
}
