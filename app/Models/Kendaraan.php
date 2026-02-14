<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kendaraan extends Model
{
    use HasUuids;

    protected $table = 'kendaraan';

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_perusahaan',
        'id_jenis_kendaraan',
        'nama',
        'plat',
    ];

    public function setPlatAttribute($value)
    {
        $this->attributes['plat'] = strtoupper($value);
    }

    // Relationships
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }
    
    public function jenisKendaraan()
    {
        return $this->belongsTo(JenisKendaraan::class, 'id_jenis_kendaraan');
    }

    public function sesiAlat()
    {
        return $this->hasMany(SesiAlat::class, 'id_kendaraan');
    }

    public function riwayatPerjalanan()
    {
        return $this->hasMany(RiwayatPerjalanan::class, 'id_kendaraan');
    }
}
