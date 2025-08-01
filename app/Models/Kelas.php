<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = ['nama_kelas', 'kompetensi_keahlian'];

    public function rekapKelas()
    {
        return $this->hasMany(RekapKelas::class, 'id_kelas');
    }

    public function tugasMengajar()
    {
        return $this->hasMany(TugasMengajar::class, 'id_kelas', 'id_kelas');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function kompetensiKeahlian()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }
}
