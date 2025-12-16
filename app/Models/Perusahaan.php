<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';
    
    protected $fillable = [
        'nama',
        'alamat',
    ];

    // Relationships
    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'id_perusahaan');
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
