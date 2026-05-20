<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Review::with(['user', 'reviewer', 'trip'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('reviewer', function ($row) {
                    return $row->reviewer->name ?? 'N/A';
                })
                ->addColumn('user', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('trip', function ($row) {
                    return $row->trip ? ($row->trip->from_location . ' → ' . $row->trip->to_location) : 'N/A';
                })
                ->editColumn('star', function ($row) {
                    return $row->star ? $row->star . ' ★' : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="btn btn-soft-danger btn-sm delete-review" data-id="' . $row->id . '" title="Delete review"><i class="ti ti-trash"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.reviews.index');
    }

    public function delete($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['success' => true]);
    }
}
