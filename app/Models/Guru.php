<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Guru extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru';

    protected $fillable = ['kode_guru', 'nama_guru', 'password', 'role'];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function rekapKelas()
    {
        return $this->hasMany(RekapKelas::class, 'id_guru');
    }

    public function tugasMengajar()
    {
        return $this->hasMany(TugasMengajar::class, 'id_guru', 'id_guru');
    }

    public function auth()
    {
        return $this->belongsTo(UserGuru::class);
    }
}
