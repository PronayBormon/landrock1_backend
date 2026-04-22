<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\TripResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProfileApiController extends Controller
{
    protected $service;
    use ApiResponse;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function updateProfile(UpdateUserProfileRequest $request)
    {
        // dd($request->all());

        $user = $this->service->updateProfile(auth()->user(), $request);

        return $this->successResponse('Profile updated successfully', new UserResource($user));
    }

    public function profile()
    {
        $user = $this->service->getProfile(auth()->user());

        return $this->successResponse('Profile updated successfully', new UserResource($user));
    }

    public function myTrip(Request $request)
    {
        // $request = $request->get('type');

        $trips = $this->service->myTripList(auth()->user(), $request);

        return $this->successResponse(
            'My trip list',
            TripResource::collection($trips)
        );
    }

    public function changePassword(Request $request)
    {
        return $this->service->changePassword($request);
    }


    public function deleteProfile(Request $request)
    {
        return $this->service->deleteProfile($request);
    }
}
