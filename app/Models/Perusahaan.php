<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Perusahaan extends Model
{
    use HasUuids;

    protected $table = 'perusahaan';

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'nama',
        'alamat',
    ];

    // Relationships
    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'id_perusahaan');
    }
    public function alat()
    {
        return $this->hasMany(Alat::class, 'id_perusahaan');
    }

    public function sesiAlat()
    {
        return $this->hasMany(SesiAlat::class, 'id_perusahaan');
    }

    public function riwayatPerjalanan()
    {
        return $this->hasMany(RiwayatPerjalanan::class, 'id_perusahaan');
    }
}
