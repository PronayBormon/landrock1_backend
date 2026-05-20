<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripBooking;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Twopoint0\ReverbChat\Models\Chat as ModelsChat;
use Twopoint0\ReverbChat\Models\Chat;
use Twopoint0\ReverbChat\Models\ChatMessage;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | USER ANALYTICS
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $verifiedUsers = User::whereNotNull('email_verified_at')
            ->count();

        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TRIP ANALYTICS
        |--------------------------------------------------------------------------
        */

        $totalTrips = Trip::count();

        $activeTrips = Trip::where('ride_status', 'active')->count();

        $completedTrips = Trip::where('ride_status', 'completed')->count();

        $cancelledTrips = Trip::where('ride_status', 'cancelled')->count();

        $todayTrips = Trip::whereDate('created_at', today())->count();

        /*
        |--------------------------------------------------------------------------
        | BOOKING ANALYTICS
        |--------------------------------------------------------------------------
        */

        $totalBookings = TripBooking::count();

        $approvedBookings = TripBooking::where('status', 'approved')->count();

        $pendingBookings = TripBooking::where('status', 'pending')->count();

        $rejectedBookings = TripBooking::where('status', 'rejected')->count();

        /*
        |--------------------------------------------------------------------------
        | REVENUE ANALYTICS
        |--------------------------------------------------------------------------
        */

        $totalRevenue = TripBooking::where('status', 'approved')
            ->sum('total_price');

        $monthlyRevenue = TripBooking::where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        /*
        |--------------------------------------------------------------------------
        | CHAT ANALYTICS
        |--------------------------------------------------------------------------
        */

        $totalChats = ModelsChat::count();

        $totalMessages = ChatMessage::count();

        $todayMessages = ChatMessage::whereDate('created_at', today())
            ->count();

        /*
        |--------------------------------------------------------------------------
        | REVIEW ANALYTICS
        |--------------------------------------------------------------------------
        */

        $totalReviews = Review::count();

        $averageRating = Review::avg('star') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | RECENT USERS
        |--------------------------------------------------------------------------
        */

        $recentUsers = User::latest()
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT TRIPS
        |--------------------------------------------------------------------------
        */

        $recentTrips = Trip::with('publisher')
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT BOOKINGS
        |--------------------------------------------------------------------------
        */

        $recentBookings = TripBooking::with([
                'user',
                'trip.publisher'
            ])
            ->latest()
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT CHATS
        |--------------------------------------------------------------------------
        */

        $recentChats = Chat::with([
                'users',
                'messages'
            ])
            ->latest()
            ->take(10)
            ->get();

        return view('backend.layouts.dashboard.index', compact(
            'totalUsers',
            'verifiedUsers',
            'newUsersThisMonth',

            'totalTrips',
            'activeTrips',
            'completedTrips',
            'cancelledTrips',
            'todayTrips',

            'totalBookings',
            'approvedBookings',
            'pendingBookings',
            'rejectedBookings',

            'totalRevenue',
            'monthlyRevenue',

            'totalChats',
            'totalMessages',
            'todayMessages',

            'totalReviews',
            'averageRating',

            'recentUsers',
            'recentTrips',
            'recentBookings',
            'recentChats'
        ));
    }
}