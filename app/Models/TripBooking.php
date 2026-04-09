<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripBooking extends Model
{
    protected $fillable = [
        "trip_id",
        "user_id",
        "seat_count",
        "total_price",
        "status",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
