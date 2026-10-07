<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRideRequestRequest;
use App\Http\Requests\UpdateRideRequestRequest;
use App\Http\Resources\RideRequestResource;
use App\Models\RideRequest;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RideRequestApiController extends Controller
{
    use ApiResponse;

    /**
     * List ride requests with tab filters, search, and sorting.
     */
    public function index(Request $request): JsonResponse
    {
        $query = RideRequest::with('user');

        // Status filter (defaults to 'active')
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        // Quick Tabs filter: all, upcoming, this_weekend, next_7_days
        $tab = strtolower(str_replace([' ', '-'], '_', $request->get('tab', 'all')));
        $today = Carbon::today();

        if ($tab === 'upcoming') {
            $query->whereDate('travel_date', '>=', $today);
        } elseif ($tab === 'this_weekend') {
            // Upcoming Saturday and Sunday
            $startOfWeekend = Carbon::now()->next(Carbon::SATURDAY);
            if (Carbon::now()->isSaturday()) {
                $startOfWeekend = Carbon::now();
            }
            $endOfWeekend = (clone $startOfWeekend)->next(Carbon::SUNDAY);
            if (Carbon::now()->isSunday()) {
                $startOfWeekend = Carbon::now()->previous(Carbon::SATURDAY);
                $endOfWeekend = Carbon::now();
            }

            $query->whereBetween('travel_date', [
                $startOfWeekend->toDateString(),
                $endOfWeekend->toDateString(),
            ]);
        } elseif ($tab === 'next_7_days') {
            $query->whereBetween('travel_date', [
                $today->toDateString(),
                $today->copy()->addDays(7)->toDateString(),
            ]);
        }

        // Filter: From City
        if ($request->filled('from')) {
            $query->where('from_location', 'like', '%' . $request->from . '%');
        }

        // Filter: To City
        if ($request->filled('to')) {
            $query->where('to_location', 'like', '%' . $request->to . '%');
        }

        // Filter: Travel Date
        if ($request->filled('travel_date')) {
            $query->whereDate('travel_date', $request->travel_date);
        }

        // Filter: Preferred Time
        if ($request->filled('preferred_time') && strtolower($request->preferred_time) !== 'anytime') {
            $query->where('preferred_time', 'like', '%' . $request->preferred_time . '%');
        }

        // Filter: Seats Needed
        if ($request->filled('seats_needed') && strtolower($request->seats_needed) !== 'any') {
            $query->where('seats_needed', (int) $request->seats_needed);
        }

        // Filter: Specific User ID
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Sorting
        $sortBy = strtolower(str_replace([' ', '-'], '_', $request->get('sort_by', 'newest')));
        switch ($sortBy) {
            case 'oldest':
            case 'oldest_first':
                $query->oldest('created_at');
                break;
            case 'date_asc':
            case 'travel_date_asc':
                $query->orderBy('travel_date', 'asc')->latest('created_at');
                break;
            case 'date_desc':
            case 'travel_date_desc':
                $query->orderBy('travel_date', 'desc')->latest('created_at');
                break;
            case 'most_seats':
            case 'seats_desc':
                $query->orderBy('seats_needed', 'desc')->latest('created_at');
                break;
            case 'newest':
            case 'newest_first':
            default:
                $query->latest('created_at');
                break;
        }

        $perPage = (int) $request->get('per_page', 10);
        $rideRequests = $query->paginate($perPage);

        $responseData = RideRequestResource::collection($rideRequests)->response()->getData(true);
        $responseData['total'] = $rideRequests->total();
        $responseData['total_requests_found'] = $rideRequests->total();

        return $this->successResponse(
            'Ride requests retrieved successfully',
            $responseData,
            200
        );
    }

    /**
     * Get authenticated user's ride requests.
     */
    public function myRequests(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $perPage = (int) $request->get('per_page', 10);

        $rideRequests = RideRequest::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);

        $responseData = RideRequestResource::collection($rideRequests)->response()->getData(true);
        $responseData['total'] = $rideRequests->total();
        $responseData['total_requests_found'] = $rideRequests->total();

        return $this->successResponse(
            'My ride requests retrieved successfully',
            $responseData,
            200
        );
    }

    /**
     * Post a new ride request.
     */
    public function store(StoreRideRequestRequest $request): JsonResponse
    {
        $user = auth()->user();

        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['status'] = 'active';

        if (empty($data['preferred_time'])) {
            $data['preferred_time'] = 'Anytime';
        }

        if (empty($data['seats_needed'])) {
            $data['seats_needed'] = 1;
        }

        $rideRequest = RideRequest::create($data);
        $rideRequest->load('user');

        return $this->successResponse(
            'Ride request posted successfully',
            new RideRequestResource($rideRequest),
            201
        );
    }

    /**
     * Show single ride request details.
     */
    public function show($id): JsonResponse
    {
        $rideRequest = RideRequest::with('user')->find($id);

        if (!$rideRequest) {
            return $this->errorResponse('Ride request not found', 404);
        }

        return $this->successResponse(
            'Ride request retrieved successfully',
            new RideRequestResource($rideRequest),
            200
        );
    }

    /**
     * Update own ride request.
     */
    public function update(UpdateRideRequestRequest $request, $id): JsonResponse
    {
        $rideRequest = RideRequest::find($id);

        if (!$rideRequest) {
            return $this->errorResponse('Ride request not found', 404);
        }

        if ($rideRequest->user_id !== auth()->id()) {
            return $this->errorResponse('You are not authorized to update this ride request', 403);
        }

        $rideRequest->update($request->validated());
        $rideRequest->load('user');

        return $this->successResponse(
            'Ride request updated successfully',
            new RideRequestResource($rideRequest),
            200
        );
    }

    /**
     * Delete own ride request.
     */
    public function destroy($id): JsonResponse
    {
        $rideRequest = RideRequest::find($id);

        if (!$rideRequest) {
            return $this->errorResponse('Ride request not found', 404);
        }

        if ($rideRequest->user_id !== auth()->id()) {
            return $this->errorResponse('You are not authorized to delete this ride request', 403);
        }

        $rideRequest->delete();

        return $this->successResponse('Ride request deleted successfully', null, 200);
    }
}
