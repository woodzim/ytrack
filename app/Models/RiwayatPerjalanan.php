<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RiwayatPerjalanan extends Model
{
    use HasUuids;

    protected $table = 'riwayat_perjalanan';

    public $incrementing = false;
    protected $keyType = 'string';

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
        'battery',
    ];

    protected $casts = [
        'lat' => 'string',
        'long' => 'string',
        'alt' => 'string',
        'sat' => 'integer',
        'speed' => 'integer',
        'battery' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the perjalanan that owns this riwayat perjalanan
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

    /**
     * Scope a query to only include uncompleted riwayat perjalanan.
     */
    public function scopeUncompleted($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('id_sesi_alat')
                ->orWhereNull('id_alat')
                ->orWhereNull('id_kendaraan')
                ->orWhereNull('id_perusahaan');
        });
    }
}

