<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\RideRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RideRequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = RideRequest::with('user')->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    if (!$row->user) {
                        return '<span class="text-muted">N/A</span>';
                    }

                    $avatarUrl = User::resolveAvatarUrl($row->user->getRawOriginal('avatar'));
                    $avatar = !empty($avatarUrl)
                        ? e($avatarUrl)
                        : asset('/backend/assets/images/user.webp');

                    return '
                        <div class="d-flex align-items-center">
                            <img src="' . $avatar . '" style="height: 38px; width: 38px; object-fit: cover;" class="rounded-circle me-2" alt="Avatar">
                            <div>
                                <h6 class="m-0 fs-14 fw-medium">' . e($row->user->name) . '</h6>
                                <span class="text-muted fs-12">' . e($row->user->email) . '</span>
                            </div>
                        </div>
                    ';
                })
                ->editColumn('travel_date', function ($row) {
                    return $row->travel_date ? Carbon::parse($row->travel_date)->format('M d, Y') : 'N/A';
                })
                ->editColumn('preferred_time', function ($row) {
                    return e($row->preferred_time ?? 'Anytime');
                })
                ->addColumn('seats_needed', function ($row) {
                    $s = (int) ($row->seats_needed ?? 1);
                    return '<span class="badge bg-soft-info text-info"><i class="ti ti-user me-1"></i>' . $s . ' ' . ($s === 1 ? 'Seat' : 'Seats') . '</span>';
                })
                ->editColumn('status', function ($row) {
                    $badges = [
                        'active'    => 'bg-success',
                        'pending'   => 'bg-warning',
                        'completed' => 'bg-info',
                        'cancelled' => 'bg-danger',
                    ];

                    $class = $badges[$row->status] ?? 'bg-secondary';
                    $label = ucfirst($row->status);

                    return '<span class="badge ' . $class . '">' . $label . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.ride-requests.edit', $row->id);

                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-soft-secondary rounded-pill me-1" title="Edit">
                            <i class="ti ti-pencil"></i>
                        </a>
                        <button class="btn btn-sm btn-soft-danger rounded-pill delete-ride-request" data-id="' . $row->id . '" title="Delete">
                            <i class="ti ti-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns(['user', 'seats_needed', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.ride_requests.index');
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('backend.layouts.ride_requests.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'from_location'  => 'required|string|max:255',
            'from_latitude'  => 'nullable|numeric',
            'from_longitude' => 'nullable|numeric',
            'to_location'    => 'required|string|max:255',
            'to_latitude'    => 'nullable|numeric',
            'to_longitude'   => 'nullable|numeric',
            'travel_date'    => 'required|date',
            'preferred_time' => 'nullable|string|max:100',
            'seats_needed'   => 'nullable|integer|min:1|max:10',
            'note'           => 'nullable|string|max:300',
            'status'         => 'required|in:active,pending,completed,cancelled',
        ]);

        if (empty($validated['preferred_time'])) {
            $validated['preferred_time'] = 'Anytime';
        }

        if (empty($validated['seats_needed'])) {
            $validated['seats_needed'] = 1;
        }

        RideRequest::create($validated);

        return redirect()
            ->route('admin.ride-requests.index')
            ->with('t-success', 'Ride request created successfully.');
    }

    public function edit($id)
    {
        $rideRequest = RideRequest::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('backend.layouts.ride_requests.edit', compact('rideRequest', 'users'));
    }

    public function update(Request $request, $id)
    {
        $rideRequest = RideRequest::findOrFail($id);

        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'from_location'  => 'required|string|max:255',
            'from_latitude'  => 'nullable|numeric',
            'from_longitude' => 'nullable|numeric',
            'to_location'    => 'required|string|max:255',
            'to_latitude'    => 'nullable|numeric',
            'to_longitude'   => 'nullable|numeric',
            'travel_date'    => 'required|date',
            'preferred_time' => 'nullable|string|max:100',
            'seats_needed'   => 'nullable|integer|min:1|max:10',
            'note'           => 'nullable|string|max:300',
            'status'         => 'required|in:active,pending,completed,cancelled',
        ]);

        if (empty($validated['preferred_time'])) {
            $validated['preferred_time'] = 'Anytime';
        }

        if (empty($validated['seats_needed'])) {
            $validated['seats_needed'] = 1;
        }

        $rideRequest->update($validated);

        return redirect()
            ->route('admin.ride-requests.index')
            ->with('t-success', 'Ride request updated successfully.');
    }

    public function destroy($id)
    {
        $rideRequest = RideRequest::findOrFail($id);
        $rideRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ride request deleted successfully.'
        ]);
    }
}
