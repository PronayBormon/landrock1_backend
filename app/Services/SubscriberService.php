<?php

namespace App\Services;

use App\Repositories\SubscriberRepository;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberService
{
    use ApiResponse;

    protected $subscriberRepo;

    public function __construct(SubscriberRepository $subscriberRepo)
    {
        $this->subscriberRepo = $subscriberRepo;
    }

    public function subscribe($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        // Use trait
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        // Check duplicate
        if ($this->subscriberRepo->exists($request->email)) {
            return $this->errorResponse('Email already subscribed', 409);
        }

        $subscriber = $this->subscriberRepo->create([
            'email' => $request->email
        ]);

        // Use success response
        return $this->successResponse(
            'Subscribed successfully',
            $subscriber,
            201
        );
    }
}
