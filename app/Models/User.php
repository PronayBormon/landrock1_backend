<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'avatar',
        'role',
        'email_verified_at',
        'remember_token',
        "bio",
        "ride_style",
        "music_preference",
        "conversation_level",
        "interested",
        "personalization",
        "smoke",
        "pet",
        "connect_like_rider",
        "what_kind_ride",
        "phone",
        "phone_verified_at",
        "co2_saved_kg",
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
            'interested' => 'array',
            'personalization' => 'array',
            'co2_saved_kg' => 'decimal:2',
        ];
    }

    public function getAvatarAttribute($value)
    {
        return static::resolveAvatarUrl($value);
    }

    public static function resolveAvatarUrl(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Fix legacy URLs double-wrapped with asset(), e.g. http://app.test/https://lh3.google...
        if (preg_match('#^https?://[^/]+/(https?://.+)$#i', $value, $matches)) {
            $value = $matches[1];
        }

        if (str_starts_with($value, '//')) {
            return 'https:'.$value;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset($value);
    }

    // Reviews received
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    // Reviews given
    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'review_by');
    }

    public function rideRequests()
    {
        return $this->hasMany(RideRequest::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors (Appended Attributes)
    |--------------------------------------------------------------------------
    */
    protected $appends = ['avg_review'];

    public function getAvgReviewAttribute()
    {
        return round($this->reviews_avg_star
            ?? $this->reviews()->avg('star')
            ?? 0, 1);
    }
}
