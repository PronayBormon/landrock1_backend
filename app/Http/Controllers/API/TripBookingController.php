<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TripService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TripBookingController extends Controller
{
    protected $service;
    use ApiResponse;

    public function __construct(TripService $service)
    {
        $this->service = $service;
    }


    public function requestSeat(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seat_count' => 'required|integer|min:1'
        ]);

        $booking = $this->service->saveBooking($request, $request->trip_id);

        return $this->successResponse('Send request for seat successfully', $booking, 201);
    }

    public function tripRequestList(Request $request)
    {

        $booking = $this->service->tripbooking($request);

        return $this->successResponse('Send request for seat successfully', $booking, 201);
    }
}
