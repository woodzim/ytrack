<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ActivityLog extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $incrementing = false;
    protected $keyType = 'string';

    // Relasi ke User (Siapa pelakunya)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}