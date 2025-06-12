<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Order;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'is_admin',
    //     'email_verified_at',
    //     'password',
    //     'first_name',
    //     'last_name',
    //     'phone',
    //     'address',
    //     'city',
    //     'country',
    //     'country_code',
    //     'remember_token',
    //     'is_active',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'is_admin' => false,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin == true;
    }


    public function getFormattedPhoneAttribute(): string
    {
        $phone = $this->phone;

        // Remove leading zero if present
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        // Combine with country code (assuming country_code is stored as '966' without '+')
        return $this->country_code . $phone;
    }

    public function getImageUrlAttribute()
    {
        $path = 'storage/' . $this->image;

        if ($this->image && file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('frontend/assets/image/avatar_male.webp');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->email ?? $this->phone ?? 'Unknown';
    }
}
