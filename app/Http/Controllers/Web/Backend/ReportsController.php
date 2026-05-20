<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Trip;
use App\Models\TripBooking;
use App\Models\User;
use Twopoint0\ReverbChat\Models\Chat;
use Twopoint0\ReverbChat\Models\ChatMessage;

class ReportsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTrips = Trip::count();
        $totalBookings = TripBooking::count();
        $totalReviews = Review::count();
        $totalChats = Chat::count();
        $totalMessages = ChatMessage::count();
        $totalRevenue = TripBooking::where('status', 'approved')->sum('total_price');

        return view('backend.layouts.reports.index', compact(
            'totalUsers',
            'totalTrips',
            'totalBookings',
            'totalReviews',
            'totalChats',
            'totalMessages',
            'totalRevenue'
        ));
    }
}
