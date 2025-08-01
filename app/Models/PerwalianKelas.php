<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerwalianKelas extends Model
{
    protected $table = 'perwalian_kelas';
    protected $fillable = [
        'id_wali_kelas',
        'id_kelas',
    ];
    public function walikelas()
    {
        return $this->belongsTo(WaliKelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }

    /**
     * Relasi ke model Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_wali_kelas', 'id_guru');
    }
}
