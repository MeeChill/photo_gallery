<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $photos = $user->photos()->latest()->get();
        $saved = $user->savedPhotos()->latest()->get();

        return view('profile.show', compact('user', 'photos', 'saved'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        auth()->user()->update($request->only('name', 'email'));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        $user = auth()->user();

        if ($user->avatar && file_exists(public_path($user->avatar))) {
            unlink(public_path($user->avatar));
        }

        $file = $request->file('avatar');
        $filename = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/avatars/' . $filename;

        $file->move(public_path('uploads/avatars/'), $filename);

        $user->update(['avatar' => $path]);

        return back()->with('success', 'Avatar updated!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'          => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated!');
    }
}
