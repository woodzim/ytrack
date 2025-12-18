<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPerjalanan extends Model
{
    protected $table = 'riwayat_perjalanan';

    protected $fillable = [
        'id_sesi_alat',
        'id_perusahaan',
        'id_kendaraan',
        'id_alat',
        'lat',
        'long',
        'alt',
        'sat',
        'speed',
    ];

    protected $casts = [
        'lat' => 'string',
        'long' => 'string',
        'alt' => 'string',
        'sat' => 'integer',
        'speed' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the sesi alat that owns this riwayat perjalanan
     */
    public function sesiAlat()
    {
        return $this->belongsTo(SesiAlat::class, 'id_sesi_alat');
    }

    /**
     * Get the perusahaan that owns this riwayat perjalanan
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    /**
     * Get the kendaraan that owns this riwayat perjalanan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    /**
     * Get the alat that owns this riwayat perjalanan
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }
}

