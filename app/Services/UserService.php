<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function updateProfile($user, Request $request)
    {
        $data = $request->only([
            'name',
            'bio',
            'ride_style',
            'music_preference',
            'conversation_level',
            'smoke',
            'pet',
            'connect_like_rider',
            'what_kind_ride',
        ]);

        // Handle arrays (form-data safe)
        $data['interested'] = $request->input('interested', []);
        $data['personalization'] = $request->input('personalization', []);

        // Handle avatar
        if ($request->hasFile('avatar')) {

            $image = $request->file('avatar');

            $fileName = time() . '.' . $image->getClientOriginalExtension();

            $file = $image->storeAs(
                'user/avatar',   // folder
                $fileName,       // filename
                'public'         // disk
            );

            $data['avatar'] = 'storage/' . $file;
        }

        return $this->repository->update($user->id, $data);
    }

    public function getProfile($user)
    {
        $data = $this->repository->find($user->id);
        return $data;
    }

    public function myTripList($user, $type = null)
    {
        return $this->repository->findTripList($user->id, $type);
    }

    public function changePassword($request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();

        // ✅ Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        // ✅ Prevent same password reuse
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'New password must be different from current password',
            ], 400);
        }

        // ✅ Update password
        $this->repository->updatePassword($user->id, $request->new_password);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }


    public function deleteProfile($request)
    {
        $user = auth()->user();

        // 🔒 Optional: require password confirmation before delete
        if ($request->has('password')) {
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password is incorrect',
                ], 400);
            }
        }

        DB::beginTransaction();

        try {
            // Delete related data if needed (optional safety)
            // $user->reviews()->delete();
            // $user->givenReviews()->delete();
            // $user->trips()->delete();

            // Delete user
            $this->repository->delete($user->id);

            // Revoke all tokens (Sanctum)
            $user->tokens()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
