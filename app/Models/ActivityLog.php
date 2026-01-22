<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke User (Siapa pelakunya)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}