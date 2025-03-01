<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserGuru extends Authenticatable
{
    protected $table = 'user_guru';

    protected $fillable = [
        'guru_id',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
