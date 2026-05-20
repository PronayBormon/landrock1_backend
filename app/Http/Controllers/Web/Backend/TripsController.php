<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TripsController extends Controller
{
    protected $service;

    public function __construct(TripService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $trips = Trip::with('publisher')->latest();

            return DataTables::of($trips)

                ->addIndexColumn()

                ->addColumn('from_location', function ($trip) {
                    return $trip->from_location ?? 'N/A';
                })

                ->addColumn('to_location', function ($trip) {
                    return $trip->to_location ?? 'N/A';
                })

                ->addColumn('publisher', function ($trip) {
                    return $trip->publisher->name ?? 'N/A';
                })

                ->editColumn('ride_status', function ($trip) {

                    if ($trip->ride_status == 'active') {
                        return '<span class="badge bg-success">Active</span>';
                    }

                    return '<span class="badge bg-danger">Inactive</span>';
                })

                ->editColumn('ride_date', function ($trip) {
                    return $trip->ride_date ?? 'N/A';
                })

                ->addColumn('co2_saved_kg', function ($trip) {
                    return number_format((float) $trip->co2_saved_kg, 2) . ' kg';
                })

                ->addColumn('action', function ($trip) {

                    $editUrl = route('admin.trips.edit', $trip->id);
                    $detailsurl = route('admin.trips.show', $trip->id);

                    return '
                    <a href="' . $editUrl . '" class="btn btn-soft-secondary btn-sm me-1">
                          <i class="ti ti-pencil"></i>
                    </a>
                        <a href="' . $detailsurl . '" class="btn btn-soft-secondary btn-sm me-1" title="Edit booking">
                            <i class="ti ti-eye"></i>
                        </a>
                ';
                })

                ->rawColumns(['ride_status', 'action'])

                ->make(true);
        }

        return view('backend.layouts.trips.index');
    }

    public function show($id)
    {
        $trip = $this->service->show($id);

        if (!$trip) {
            abort(404);
        }

        $trip->load([
            'publisher',
            'reviews.user',
            'bookings.user'
        ]);

        $authUser = auth()->user();

        $match = $this->service->calculateMatch(
            $authUser,
            $trip->publisher
        );

        $trip->match_percentage = $match['percentage'];
        $trip->matches = $match['matches'];

        return view('backend.layouts.trips.show', compact('trip'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'ride_status' => 'required|in:active,completed,cancelled',
        ]);

        $trip = $this->service->show($id);

        if (!$trip) {
            return redirect()->back()
                ->with('error', 'Trip not found');
        }

        $this->service->updateStatus($id, $request->ride_status);

        return redirect()->back()
            ->with('success', 'Trip status updated successfully');
    }

    public function edit($id)
    {
        $trip = $this->service->show($id);

        if (!$trip) {
            abort(404);
        }

        return view('backend.layouts.trips.edit', compact('trip'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'from_location'     => 'required|string',
            'to_location'       => 'required|string',
            'ride_date'         => 'required',
            'ride_time'         => 'required',
            'available_seat'    => 'required|integer',
            'total_seat'        => 'required|integer',
            'price_per_seat'    => 'required|numeric',
            'car_name'          => 'required',
            'color'             => 'required',
        ]);

        $this->service->update($id, $request->all());

        return redirect()
            ->route('admin.trips.index')
            ->with('t-success', 'Trip updated successfully');
    }
}
