<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    /** @use HasFactory<\Database\Factories\GuruMapelFactory> */
    use HasFactory;
    //add fillables
    protected $fillable = ['Nip', 'nama guru', 'mapel', 'kelas'];
}
