<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $photos = $user->photos()->latest()->paginate(12);
        return view('profile.show', compact('user', 'photos'));
    }
}
