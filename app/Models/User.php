<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'phone',
        'address',
        'position',
        'usertype',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    /**
     * Accessor for profile photo URL.
     * Returns the user's profile photo URL or the default photo URL if not set.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        // Si existe la ruta y el archivo físico en la carpeta public
        if ($this->profile_photo_path && File::exists(public_path($this->profile_photo_path))) {
            // Devolver la URL pública usando asset()
            return asset($this->profile_photo_path);
        }
        // Si no, devolver la imagen predeterminada
        else {
            // Asegúrate que esta ruta a la imagen predeterminada sea correcta dentro de tu carpeta public/
            return asset('admin/img/user.jpg');
        }
    }
}
