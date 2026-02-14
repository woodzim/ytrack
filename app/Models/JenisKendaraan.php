<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JenisKendaraan extends Model
{
    use HasUuids;

    protected $table = 'jenis_kendaraan';

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'nama',
        'kategori',
    ];
}
