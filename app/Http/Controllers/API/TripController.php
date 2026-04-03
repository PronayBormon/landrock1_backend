<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Http\Resources\TripResource;
use App\Services\TripService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
    protected $service;
    use ApiResponse;

    public function __construct(TripService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $trips = $this->service->list($perPage);

        return $this->successResponse(
            'Trip List',
            TripResource::collection($trips),
            200
        );
    }

    public function store(StoreTripRequest $request)
    {
        $data = $request->validated();
        $data['publisher_id'] = auth()->id();

        $trip = $this->service->create($data);

        return new TripResource($trip);
    }

    public function show($id)
    {
        $trip = $this->service->show($id);

        return $this->successResponse(
            'Trip fetched successfully',
            new TripResource($trip)
        );
    }

    public function update(UpdateTripRequest $request, $id)
    {
        $trip = $this->service->update($id, $request->validated());

        return $this->successResponse('Trip Update successfully', new TripResource($trip));
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return $this->successResponse('Trip deleted successfully');
    }
}
