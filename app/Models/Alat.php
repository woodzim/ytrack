<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $table = 'alat';
    
    protected $primaryKey = 'id';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
    ];

    // Relationships
    public function sesiAlat()
    {
        return $this->hasMany(SesiAlat::class, 'id_alat');
    }

    public function riwayatPerjalanan()
    {
        return $this->hasMany(RiwayatPerjalanan::class, 'id_alat');
    }
}
