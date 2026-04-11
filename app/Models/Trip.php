<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'from_location',
        'from_latitude',
        'from_longitude',
        'to_location',
        'to_latitude',
        'to_longitude',
        'ride_date',
        'ride_time',
        'available_seat',
        'price_per_seat',
        'ride_status',
        'total_seat',
        'publisher_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
