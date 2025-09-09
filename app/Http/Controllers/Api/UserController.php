<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function profile()
    {
        $user = auth()->user();

        return $this->sendResponse($user, 'User profile retrieved successfully.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only('name', 'email', 'bio');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->extension();
            $avatar->move(public_path('avatars'), $avatarName);

            // Hapus avatar lama jika ada
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $data['avatar'] = 'avatars/' . $avatarName;
        }

        $user->update($data);

        return $this->sendResponse($user, 'Profile updated successfully.');
    }

    public function photos(Request $request)
    {
        $user = auth()->user();

        $photos = $user->photos()
            ->with(['likes', 'saves'])
            ->latest()
            ->paginate($request->per_page ?? 20);

        return $this->sendResponse($photos, 'User photos retrieved successfully.');
    }

    public function savedPhotos(Request $request)
    {
        $user = auth()->user();

        $savedPhotos = $user->savedPhotos()
            ->with('photo.user')
            ->latest()
            ->paginate($request->per_page ?? 20);

        return $this->sendResponse($savedPhotos, 'Saved photos retrieved successfully.');
    }

    public function likedPhotos(Request $request)
    {
        $user = auth()->user();

        $likedPhotos = $user->likes()
            ->with('photo.user')
            ->latest()
            ->paginate($request->per_page ?? 20);

        return $this->sendResponse($likedPhotos, 'Liked photos retrieved successfully.');
    }
}
