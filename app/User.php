<?php

namespace App;

use App\Transformers\UserTransformer;
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

    public const VERIFIED_USER = '1';

    public const UNVERIFIED_USER = '0';

    public const ADMIN_USER = 'true';

    public const REGULAR_USER = 'false';

    public string $transformer = UserTransformer::class;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
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

    public function setNameAttribute($name): void
    {
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name): string
    {
        return ucwords($name);
    }

    public function setEmailAttribute($email): void
    {
        $this->attributes['email'] = strtolower($email);
    }

//    public function getEmailAttribute($email)
//    {
//        return ucwords($email);
//    }

    public function isVerified(): bool
    {
        return $this->verified === self::VERIFIED_USER;
    }

    public function isAdmin(): bool
    {
        return $this->admin === self::ADMIN_USER;
    }

    public static function generateVerificationCode(): string
    {
        return Str::random(40);
    }
}
