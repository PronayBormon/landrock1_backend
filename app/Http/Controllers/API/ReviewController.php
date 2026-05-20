<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use App\Traits\ApiResponse;

class ReviewController extends Controller
{
    use ApiResponse;
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
    public function list()
    {
        $reviews = Review::with([
            "user",
            "reviewer",
        ])->latest()->limit(10)->where('star', 5)->get();
        return $this->successResponse('review list featch sucessfully', $reviews);
    }
}
