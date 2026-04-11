<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        // For DataTable AJAX
        if ($request->ajax()) {
            $data = Subscriber::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M Y, h:i A');
                })

                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-danger btn-sm delete-subscriber"
                            data-id="'.$row->id.'">
                            Delete
                        </button>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        // Load blade normally
        return view('backend.layouts.subscribers.index');
    }

    public function delete($id)
    {
        Subscriber::findOrFail($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
