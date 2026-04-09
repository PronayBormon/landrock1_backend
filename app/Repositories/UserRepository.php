<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;

class UserRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function update($userId, array $data)
    {
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user->fresh();
    }

    public function find($userId)
    {
        return User::findOrFail($userId);
    }

    public function findTripList($userId, $type = null)
    {
        $query = Trip::where('publisher_id', $userId)
            ->with('publisher')
            ->orderBy('id', 'desc');

        // ✅ Filter by type
        if ($type->type === 'upcoming') {
            $query->whereDate('ride_date', '>=', Carbon::today());
        }

        if ($type->type === 'completed') {
            $query->whereDate('ride_date', '<', Carbon::today());
        }

        return $query->paginate($type->per_page ?? 10);
    }
}
