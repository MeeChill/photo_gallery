<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Photo;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Photo $photo)
    {
        $user = auth()->guard()->user();

        // Check if user already liked the photo
        $existingLike = Like::where('user_id', $user->id)
                            ->where('photo_id', $photo->id)
                            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $liked = false;
        } else {
            // Like
            Like::create([
                'user_id' => $user->id,
                'photo_id' => $photo->id
            ]);
            $liked = true;
        }

        $likeCount = Like::where('photo_id', $photo->id)->count();

        return response()->json([
            'liked' => $liked,
            'count' => $likeCount
        ]);
    }
}
