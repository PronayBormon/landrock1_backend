<?php

namespace App\Http\Controllers\Web\Backend\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class usercontroller extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    public function userlist(Request $request)
    {
        if ($request->ajax()) {


            $query = User::query();

            // Join Date Filter
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('created_at', [
                    $request->from_date . ' 00:00:00',
                    $request->to_date . ' 23:59:59'
                ]);
            }

            // dd(User::all());
            return DataTables::of($query)
                ->addColumn('role', fn($row) => ucfirst($row->role))
                ->addColumn('created_at', fn($row) => Carbon::parse($row->created_at)->format('M d, Y'))
                ->addColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('avatar', function ($row) {
                    if (!empty($row->avatar)) {
                        return '<img src="' . asset($row->avatar) . '" style="    height: 50px; background: red; width: 50px; object-fit: cover; " alt="" class="img-fluid rounded-circle">';
                    } else {
                        return '<img src="' . asset('/backend/assets/images/user.webp') . '" style="    height: 50px; width: 50px; object-fit: cover;" alt="" class="img-fluid rounded-circle">';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.users.edit', $row->id) . '"
                        class="btn btn-sm btn-soft-secondary rounded-pill me-1"
                        title="Edit">
                            <i class="ri-pencil-line"></i>
                        </a>

                        <button class="btn btn-sm btn-soft-danger rounded-pill delete-page"
                                data-id="' . $row->id . '"
                                title="Delete">
                            <i class="ri-delete-bin-7-line"></i>
                        </button>
                    ';
                })
                ->rawColumns(['action', 'status', 'avatar'])

                ->make(true);
        }

        return view('backend.layouts.users.list');
    }

    public function usercreate()
    {
        return view('backend.layouts.users.create');
    }

    public function useredit($id)
    {
        $user = User::find($id);
        return view('backend.layouts.users.edit', compact('user'));
    }


    public function userstore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            'avatar' => 'nullable',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'avatar' => $request->avatar,
        ]);

        return redirect()->route('admin.users.index')->with('t-success', 'User created successfully.');
    }

    public function userupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required',

            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',

            'ride_style' => 'nullable',
            'music_preference' => 'nullable',
            'conversation_level' => 'nullable',
            'smoke' => 'nullable',

            'pet' => 'nullable|string',
            'connect_like_rider' => 'nullable|string',
            'what_kind_ride' => 'nullable|string',
        ]);

        $this->service->adminUpdateProfile(
            $user,
            $request
        );

        return redirect()
            ->route('admin.users.index')
            ->with('t-success', 'User updated successfully.');
    }
}
