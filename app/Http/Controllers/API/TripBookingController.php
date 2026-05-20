<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TripService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
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

        if ($booking instanceof JsonResponse) {
            return $booking;
        }

        return $this->successResponse('Send request for seat successfully', $booking, 201);
    }

    public function tripRequestList(Request $request)
    {

        $booking = $this->service->tripbooking($request);

        return $this->successResponse('Send request for seat successfully', $booking, 201);
    }

    public function joinedTripList(Request $request)
    {
        $booking = $this->service->joinedTrips($request);

        return $this->successResponse('My joined trip list', $booking, 200);
    }

    public function tripusersList(Request $request, $id)
    {

        $booking = $this->service->tripusers($request, $id);

        if ($booking instanceof JsonResponse) {
            return $booking;
        }

        return $this->successResponse('Featch user list', $booking, 201);
    }

    public function tripRequestAccept($id)
    {

        $booking = $this->service->triprequest('approved', $id);

        if ($booking instanceof JsonResponse) {
            return $booking;
        }

        return $this->successResponse('Trip seat request accept successfully', $booking, 200);
    }

    public function tripRequestReject($id)
    {

        $booking = $this->service->triprequest('rejected', $id);

        if ($booking instanceof JsonResponse) {
            return $booking;
        }

        return $this->successResponse('Trip seat request reject successfully', $booking, 200);
    }
}
