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
        ];
    }

    public function getAvatarAttribute($value)
    {
        if (!empty($value)) {
            return asset($value);
        }

        return $value;
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
