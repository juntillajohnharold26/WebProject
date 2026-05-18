<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_role',
        'profile_location',
        'profile_bio',
        'profile_avatar',
        'profile_status',
        'devsell_active',
        'devsell_status',
        'devsell_joined_at',
        'devsell_display_name',
        'devsell_store_name',
        'devsell_specialty',
        'devsell_portfolio',
        'devsell_bio',
        'is_admin',
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
            'profile_status' => 'string',
            'devsell_active' => 'boolean',
            'devsell_status' => 'string',
            'devsell_joined_at' => 'datetime',
            'is_admin' => 'boolean',
        ];
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function templateListings(): HasMany
    {
        return $this->hasMany(TemplateListing::class);
    }

    public function marketplaceNotifications(): HasMany
    {
        return $this->hasMany(MarketplaceNotification::class);
    }
}
