<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class ReviewService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    protected $reviewRepo;

    public function __construct(ReviewRepository $reviewRepo)
    {
        $this->reviewRepo = $reviewRepo;
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
            'review'  => 'nullable|string',
            'star'    => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        // prevent self review
        if ($request->user_id == auth()->id()) {
            return $this->errorResponse('You cannot review yourself', 400);
        }

        // prevent duplicate review
        if ($this->reviewRepo->alreadyReviewed($request->user_id, auth()->id(), $request->trip_id)) {
            return $this->errorResponse('Already reviewed this trip', 400);
        }

        $data = [
            'user_id'   => $request->user_id,
            'review_by' => auth()->id(),
            'trip_id'   => $request->trip_id,
            'review'    => $request->review,
            'star'      => $request->star,
        ];

        $review = $this->reviewRepo->create($data);

        return $this->successResponse('Review successfull', $review, 200);
    }

    public function getByTrip($tripId)
    {
        $reviews = $this->reviewRepo->getByTrip($tripId);
        return $this->successResponse('Review successfull', $reviews, 200);
    }

    public function delete($id)
    {
        $review = $this->reviewRepo->find($id);

        if (!$review) {
            return $this->errorResponse('Review not found', 404);
        }

        // only owner can delete
        if ($review->review_by != auth()->id()) {

            return $this->errorResponse('Unauthenticated', 403);
        }

        $this->reviewRepo->delete($id);
        return $this->successResponse('Review deleted successfully', 200);
    }
}
