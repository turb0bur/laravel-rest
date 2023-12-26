<?php

namespace App\Models;

use App\Transformers\UserTransformer;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use HasFactory;

    public const VERIFIED_USER = true;

    public const UNVERIFIED_USER = false;

    public const ADMIN_USER = true;

    public const REGULAR_USER = false;

    public string $transformer = UserTransformer::class;

    protected $table = 'users';

    protected $casts = [
        'is_admin' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_verified',
        'verification_token',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function setNameAttribute(string $name): void
    {
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute(string $name): string
    {
        return ucwords($name);
    }

    public function setEmailAttribute(string $email): void
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function isVerified(): bool
    {
        return $this->is_verified === self::VERIFIED_USER;
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === self::ADMIN_USER;
    }

    public static function generateVerificationCode(): string
    {
        return Str::random(40);
    }
}
