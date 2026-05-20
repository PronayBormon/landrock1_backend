<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twopoint0\ReverbChat\Models\Chat;
use Yajra\DataTables\Facades\DataTables;

class ChatsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Chat::with(['users', 'messages'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('participants', function ($row) {
                    return $row->users->pluck('name')->filter()->implode(', ') ?: 'N/A';
                })
                ->addColumn('messages', function ($row) {
                    return $row->messages->count();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.chats.show', $row->id) . '" class="btn btn-soft-secondary btn-sm" title="View chat"><i class="ti ti-eye"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.chats.index');
    }

    public function show($id)
    {
        $chat = Chat::with(['users', 'messages.sender'])->findOrFail($id);

        return view('backend.layouts.chats.show', compact('chat'));
    }
}
