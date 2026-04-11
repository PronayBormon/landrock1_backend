<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(Request $request)
    {
        return $this->reviewService->store($request);
    }

    public function getByTrip($tripId)
    {
        return $this->reviewService->getByTrip($tripId);
    }

    public function delete($id)
    {
        return $this->reviewService->delete($id);
    }
}
