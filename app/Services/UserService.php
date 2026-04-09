<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function updateProfile($user, Request $request)
    {
        $data = $request->only([
            'name',
            'bio',
            'ride_style',
            'music_preference',
            'conversation_level',
            'smoke',
            'pet',
            'connect_like_rider',
            'what_kind_ride',
        ]);

        // Handle arrays (form-data safe)
        $data['interested'] = $request->input('interested', []);
        $data['personalization'] = $request->input('personalization', []);

        // Handle avatar
        if ($request->hasFile('avatar')) {
            $data['avatar'] = 'storage/' . $request->file('avatar')->store('avatars', 'public');
        }

        return $this->repository->update($user->id, $data);
    }

    public function getProfile($user)
    {
        $data = $this->repository->find($user->id);
        return $data;
    }

    public function myTripList($user, $type = null)
    {
        return $this->repository->findTripList($user->id, $type);
    }
}
