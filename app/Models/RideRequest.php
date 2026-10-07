<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_location',
        'from_latitude',
        'from_longitude',
        'to_location',
        'to_latitude',
        'to_longitude',
        'travel_date',
        'preferred_time',
        'note',
        'seats_needed',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'seats_needed' => 'integer',
            'from_latitude' => 'decimal:7',
            'from_longitude' => 'decimal:7',
            'to_latitude' => 'decimal:7',
            'to_longitude' => 'decimal:7',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
