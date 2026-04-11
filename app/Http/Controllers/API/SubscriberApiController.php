<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubscriberService;
use Illuminate\Http\Request;

class SubscriberApiController extends Controller
{
    protected $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    public function subscribe(Request $request)
    {
        return $this->subscriberService->subscribe($request);
    }
}
