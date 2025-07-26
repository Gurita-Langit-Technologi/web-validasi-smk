<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    /** @use HasFactory<\Database\Factories\GuruMapelFactory> */
    use HasFactory;
    //add fillables
    protected $fillable = ['id_guru', 'kode_guru', 'kode_mapel'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'kode_mapel', 'id_mapel');
    }
}
