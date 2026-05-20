<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\TripBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TripBooking::with(['user', 'trip'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('trip', function ($row) {
                    return $row->trip ? ($row->trip->from_location . ' → ' . $row->trip->to_location) : 'N/A';
                })
                ->editColumn('total_price', function ($row) {
                    return '$' . number_format($row->total_price, 2);
                })
                ->addColumn('status', function ($row) {
                    $statusClass = 'secondary';
                    if ($row->status === 'approved') {
                        $statusClass = 'success';
                    } elseif ($row->status === 'pending') {
                        $statusClass = 'warning';
                    } elseif ($row->status === 'rejected') {
                        $statusClass = 'danger';
                    }

                    return '<span class="badge bg-' . $statusClass . '">' . ucfirst($row->status) . '</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.bookings.edit', $row->id) . '" class="btn btn-soft-secondary btn-sm me-1" title="Edit booking">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-soft-danger btn-sm delete-booking" data-id="' . $row->id . '" title="Delete booking">
                            <i class="ti ti-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.bookings.index');
    }

    public function edit($id)
    {
        $booking = TripBooking::with(['user', 'trip'])->findOrFail($id);

        return view('backend.layouts.bookings.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = TripBooking::with('trip')->findOrFail($id);
        $trip = $booking->trip;

        $validated = $request->validate([
            'seat_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,approved,rejected,cancelled',
        ]);

        if (!$trip) {
            return back()->with('t-error', 'Booking trip not found.');
        }

        try {
            DB::transaction(function () use ($booking, $trip, $validated) {
                $oldStatus = $booking->status;
                $oldSeatCount = $booking->seat_count;
                $newStatus = $validated['status'];
                $newSeatCount = $validated['seat_count'];
                $availableSeat = $trip->available_seat;

                if ($oldStatus === 'approved' && $newStatus !== 'approved') {
                    $availableSeat += $oldSeatCount;
                }

                if ($oldStatus !== 'approved' && $newStatus === 'approved') {
                    $availableSeat -= $newSeatCount;
                }

                if ($oldStatus === 'approved' && $newStatus === 'approved' && $newSeatCount !== $oldSeatCount) {
                    $availableSeat -= ($newSeatCount - $oldSeatCount);
                }

                if ($availableSeat < 0) {
                    throw new \Exception('Not enough available seats for this update.');
                }

                $trip->update(['available_seat' => $availableSeat]);

                $booking->update([
                    'seat_count' => $newSeatCount,
                    'total_price' => $trip->price_per_seat ? $trip->price_per_seat * $newSeatCount : $booking->total_price,
                    'status' => $newStatus,
                ]);
            });
        } catch (\Exception $exception) {
            return back()->with('t-error', $exception->getMessage());
        }

        return redirect()->route('admin.bookings.index')->with('t-success', 'Booking updated successfully.');
    }

    public function delete($id)
    {
        $booking = TripBooking::findOrFail($id);
        $booking->delete();

        return response()->json(['success' => true]);
    }
}
