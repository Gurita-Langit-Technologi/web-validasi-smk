<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class WaliKelas extends Authenticatable
{
    protected $table = 'wali_kelas';
    protected $primaryKey = 'id_wali_kelas';
    protected $fillable = [
        'kode_wali',
        'password',
    ];


    public function perwalian_kelas()
    {
        return $this->belongsTo(PerwalianKelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }

    public function rekapKelas()
    {
        return $this->hasMany(RekapKelas::class, 'id_wali_kelas', 'id_wali_kelas');
    }
}
