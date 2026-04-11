<?php

namespace App\Services;

use App\Repositories\TripRepository;
use App\Traits\ApiResponse;

class TripService
{
    protected $tripRepo;
    use ApiResponse;

    public function __construct(TripRepository $tripRepo)
    {
        $this->tripRepo = $tripRepo;
    }


    public function list($perPage, $filters = [])
    {
        $trips = $this->tripRepo->all($perPage, $filters);

        $authUser = auth()->user();

        $trips->getCollection()->transform(function ($trip) use ($authUser) {

            $match = $this->calculateMatch($authUser, $trip->publisher);

            $trip->match_percentage = $match['percentage'];
            $trip->matches = $match['matches'];

            return $trip;
        });

        return $trips;
    }

    public function create($data)
    {
        // dd($data['available_seat']);
        $data['total_seat'] = $data['available_seat'];
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

    public function complete($id)
    {
        $trip = $this->tripRepo->find($id);

        
        if (!$trip) {
            return $this->errorResponse('Trip not found');
        }

        $trip->update([
            'ride_status' => 'completed'
        ]);
        
        return $trip;
    }
    public function cancel($id)
    {
        $trip = $this->tripRepo->find($id);

        
        if (!$trip) {
            return $this->errorResponse('Trip not found');
        }

        $trip->update([
            'ride_status' => 'cancelled'
        ]);
        
        return $trip;
    }

    public function calculateMatch($authUser, $publisher)
    {
        if (!$authUser || !$publisher) {
            return [
                'percentage' => 0,
                'matches' => []
            ];
        }

        $matches = [];
        $score = 0;
        // dd($authUser, $publisher);
        /*
        |--------------------------------------------------------------------------
        | 1. ROUTE COMPATIBILITY (70%)
        |--------------------------------------------------------------------------
        | Assume you already calculate route match somewhere
        | Example: $routeMatchPercent = 0–100
        */
        $routeMatchPercent = $this->calculateRouteMatch($authUser, $publisher); // return 0–100
        $routeScore = ($routeMatchPercent / 100) * 70;

        if ($routeMatchPercent > 0) {
            $matches[] = 'route_match';
        }

        /*
        |--------------------------------------------------------------------------
        | 2. VIBE (20%)
        |--------------------------------------------------------------------------
        | music_preference + conversation_level + ride_style
        */
        $vibeScore = 0;
        $vibeTotal = 3;

        // 🎵 MUSIC (enum, not array)
        if (
            $authUser->music_preference &&
            $authUser->music_preference === $publisher->music_preference
        ) {
            $vibeScore++;
            $matches[] = $authUser->music_preference;
        }

        // 💬 CONVERSATION
        if (
            $authUser->conversation_level &&
            $authUser->conversation_level === $publisher->conversation_level
        ) {
            $vibeScore++;
            $matches[] = $authUser->conversation_level;
        }

        // 🚗 RIDE STYLE
        if (
            $authUser->ride_style &&
            $authUser->ride_style === $publisher->ride_style
        ) {
            $vibeScore++;
            $matches[] = $authUser->ride_style;
        }

        $vibeFinal = ($vibeScore / $vibeTotal) * 20;

        /*
        |--------------------------------------------------------------------------
        | 3. PREFERENCES (10%)
        |--------------------------------------------------------------------------
        | smoke, pet, etc.
        */
        $prefScore = 0;
        $prefTotal = 2;

        // 🚬 SMOKE
        if (
            $authUser->smoke &&
            $authUser->smoke === $publisher->smoke
        ) {
            $prefScore++;
            $matches[] = 'smoke_' . $authUser->smoke;
        }

        // 🐶 PET
        if (
            $authUser->pet &&
            $authUser->pet === $publisher->pet
        ) {
            $prefScore++;
            $matches[] = 'pet_' . $authUser->pet;
        }

        $prefFinal = ($prefScore / $prefTotal) * 10;

        /*
        |--------------------------------------------------------------------------
        | FINAL SCORE
        |--------------------------------------------------------------------------
        */
        $score = $routeScore + $vibeFinal + $prefFinal;

        return [
            'percentage' => round($score),
            'matches' => array_values(array_unique($matches))
        ];
    }

    private function calculateRouteMatch($authUser, $publisher)
    {
        // Example logic (replace with real geo logic)
        if ($authUser->what_kind_ride === $publisher->what_kind_ride) {
            return 100;
        }

        return 0;
    }

    public function saveBooking($request, $id)
    {

        $trip = $this->tripRepo->find($id);

        if ($trip->available_seat < $request->seat_count) {
            return $this->errorResponse('Not enough seats available');
        }

        $totalPrice = $trip->price_per_seat * $request->seat_count;

        $trip = $this->tripRepo->booking($trip, $request, $totalPrice);

        return $trip;
    }

    public function tripbooking($request)
    {
        $trip = $this->tripRepo->mytrips($request, auth()->id());
        return $trip;
    }


    public function tripusers($request, $id)
    {
        $trip = $this->tripRepo->find($id);

        if (!$trip) {
            return $this->errorResponse('Trip Not found');
        }
        if ($trip->publisher_id != auth()->id()) {
            return $this->errorResponse('Trip Not found');
        }

        $users = $this->tripRepo->mytripUsers($request, $trip->id);

        return $users;
    }

    public function triprequest($status, $id)
    {
        $tripbooking = $this->tripRepo->tripbooking($id);

        if (!$tripbooking) {
            return $this->errorResponse('Booking Not found');
        }
        $avlb_seat = null;
        if ($status == 'approved') {
            $avlb_seat = $tripbooking->trip->available_seat - $tripbooking->seat_count;
        } elseif ($status == 'rejected' && $tripbooking->status == 'approved') {
            $avlb_seat = $tripbooking->trip->available_seat + $tripbooking->seat_count;
        } else {
            $avlb_seat = $tripbooking->trip->available_seat;
        }

        $tripbooking->update(['status' => $status]);
        $tripbooking->trip->update(['available_seat' => $avlb_seat]);
        return $tripbooking;
    }
}
