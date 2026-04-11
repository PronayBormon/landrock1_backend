<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'review_by',
        'trip_id',
        'review',
        'star'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // The person being reviewed
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The reviewer
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'review_by');
    }

    // Related trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
