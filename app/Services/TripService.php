<?php

namespace App\Services;

use App\Repositories\TripRepository;

class TripService
{
    protected $tripRepo;

    public function __construct(TripRepository $tripRepo)
    {
        $this->tripRepo = $tripRepo;
    }

    
    // public function list($perpage)
    // {
    //     return $this->tripRepo->all($perpage);
    // }
    public function list($perPage)
    {
        $trips = $this->tripRepo->all($perPage);

        $authUser = auth()->user();

        $trips->getCollection()->transform(function ($trip) use ($authUser) {

            $match = $this->calculateMatch($authUser, $trip->user);

            $trip->match_percentage = $match['percentage'];
            $trip->matches = $match['matches']; // ✅ FIXED

            return $trip;
        });

        return $trips;
    }

    public function create($data)
    {
        // dd($data);
        return $this->tripRepo->create($data);
    }

    public function show($id)
    {
        return $this->tripRepo->find($id);
    }

    public function update($id, $data)
    {
        return $this->tripRepo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->tripRepo->delete($id);
    }

    public function calculateMatch($authUser, $publisher)
    {
        $authProfile = $authUser;
        $publisherProfile = $publisher;

        if (!$authProfile || !$publisherProfile) {
            return [
                'percentage' => 0,
                'matches' => []
            ];
        }

        $matches = [];
        $score = 0;
        $total = 3;

        // 🎵 MUSIC
        $musicMatch = array_intersect(
            $authProfile->music ?? [],
            $publisherProfile->music ?? []
        );

        if (!empty($musicMatch)) {
            $matches = array_merge($matches, $musicMatch);
            $score++;
        }

        // 💬 CONVERSATION
        if ($authProfile->conversation === $publisherProfile->conversation) {
            $matches[] = $authProfile->conversation;
            $score++;
        }

        // 🚗 RIDE STYLE
        $rideStyleMatch = array_intersect(
            $authProfile->ride_style ?? [],
            $publisherProfile->ride_style ?? []
        );

        if (!empty($rideStyleMatch)) {
            $matches = array_merge($matches, $rideStyleMatch);
            $score++;
        }

        return [
            'percentage' => round(($score / $total) * 100),
            'matches' => array_values(array_unique($matches))
        ];
    }
}
