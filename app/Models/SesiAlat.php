<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiAlat extends Model
{
    protected $table = 'sesi_alat';
    
    protected $fillable = [
        'id_alat',
        'id_kendaraan',
        'id_perusahaan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relationships
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    public function riwayatPerjalanan()
    {
        return $this->hasMany(RiwayatPerjalanan::class, 'id_sesi_alat');
    }
}
