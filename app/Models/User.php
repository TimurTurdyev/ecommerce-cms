<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    public const ROLES = [
        'admin' => 'admin',
        'worker' => 'worker',
    ];

    protected $fillable = [
        'email',
        'department_id',
        'password',
        'name',
        'avatar',
        'role',
        'token',
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
        'token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function avatar(): Attribute
    {
        $avatar = $this->defaultAvatar();

        return Attribute::make(
            get: static fn() => $avatar,
        );
    }

    public function defaultAvatar(): string
    {
        return asset('images/avatar'.getDefaultIconId($this->id).'.svg');
    }

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
        ];
    }
}
