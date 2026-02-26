<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_perusahaan',
        'is_validated',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_validated' => 'boolean',
        ];
    }

    /**
     * Cek apakah user sudah tervalidasi (hanya untuk role perusahaan)
     */
    public function isValidated(): bool
    {
        // Jika rolenya perusahaan, cek is_validated
        if ($this->hasRole('perusahaan')) {
            return $this->is_validated;
        }

        // Selain perusahaan, anggap selalu tervalidasi
        return true;
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    /**
     * Get the user's avatar URL using UI Avatars.
     */
    public function getAvatarUrlAttribute(): string
    {
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=0F2854&color=fff&bold=true&format=svg";
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }
}
