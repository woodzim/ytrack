<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KendaraanImage extends Model
{
    use HasUuids;

    protected $fillable = [
        'kendaraan_id',
        'image_path',
        'caption',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
