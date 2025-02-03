<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapKelas extends Model
{
    protected $table = 'rekap_kelas';
    protected $primaryKey = 'id_rekap';

    protected $fillable = ['id_kelas', 'id_mapel', 'id_guru'];

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

}
