<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Models\TripBooking;

class TripRepository
{
    public function all($perPage, $filters = [])
    {
        $query = Trip::with('publisher');

        // ✅ Filter by ride date
        if (!empty($filters['ride_date'])) {
            $query->whereDate('ride_date', $filters['ride_date']);
        }

        // ✅ Filter by publisher preferences
        $query->whereHas('publisher', function ($q) use ($filters) {

            if (!empty($filters['conversation_level'])) {
                $q->where('conversation_level', $filters['conversation_level']);
            }

            if (!empty($filters['music_preference'])) {
                $q->where('music_preference', $filters['music_preference']);
            }

            if (!empty($filters['ride_style'])) {
                $q->where('ride_style', $filters['ride_style']);
            }
        });

        return $query->latest()->paginate($perPage);
    }

    public function find($id)
    {
        return Trip::findOrFail($id);
    }

    public function create(array $data)
    {
        return Trip::create($data);
    }

    public function update($id, array $data)
    {
        $trip = $this->find($id);
        $trip->update($data);
        return $trip;
    }

    public function delete($id)
    {
        return Trip::destroy($id);
    }


    public function booking($trip, $request, $totalPrice)
    {
        $booking = TripBooking::create([
            'trip_id' => $trip->id,
            'user_id' => auth()->id(),
            'seat_count' => $request->seat_count,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return $booking;
    }

    public function tripbooking($id)
    {
        $bookingRequest = TripBooking::with([
            'trip',
            'user',
        ])->where('id', $id)->first();

        return $bookingRequest;
    }

    public function mytrips($request, $userid)
    {
        $tripsIds = Trip::where('publisher_id', $userid)->orderby('id', 'desc')->pluck('id');

        $bookingRequest = TripBooking::with([
            'trip',
            'user',
        ])->whereIn('trip_id', $tripsIds)->orderby('id', 'desc')->paginate($request->perPage ?? 10);

        return $bookingRequest;
    }

    public function mytripUsers($request, $tripid)
    {
        $bookingRequest = TripBooking::where('trip_id', $tripid)->paginate($request->items ?? 10);
        // dd($bookingRequest);
        return $bookingRequest;
    }
}
