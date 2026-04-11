<?php

namespace App\Repositories;

use App\Models\Review;

class ReviewRepository
{
    /**
     * Create a new class instance.
     */
    public function create($data)
    {
        return Review::create($data);
    }

    public function find($id)
    {
        return Review::find($id);
    }

    public function delete($id)
    {
        return Review::where('id', $id)->delete();
    }

    public function getByTrip($tripId)
    {
        return Review::with(['user', 'reviewer'])
            ->where('trip_id', $tripId)
            ->latest()
            ->get();
    }

    public function alreadyReviewed($userId, $reviewBy, $tripId)
    {
        return Review::where([
            'user_id'   => $userId,
            'review_by' => $reviewBy,
            'trip_id'   => $tripId,
        ])->exists();
    }
}
