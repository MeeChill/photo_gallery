<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Photo;
use Illuminate\Http\Request;

class LikeController extends BaseController
{
    public function toggle(Request $request, $photoId)
    {
        $photo = Photo::findOrFail($photoId);
        $user = auth()->user();

        if ($photo->isLiked()) {
            $photo->likes()->detach($user->id);
            $liked = false;
            $message = 'Photo unliked successfully';
        } else {
            $photo->likes()->attach($user->id);
            $liked = true;
            $message = 'Photo liked successfully';
        }

        return $this->sendResponse([
            'liked' => $liked,
            'count' => $photo->likes()->count()
        ], $message);
    }

    public function userLikes(Request $request)
    {
        $user = auth()->user();

        $likedPhotos = $user->likes()
            ->with('photo.user')
            ->latest()
            ->paginate($request->per_page ?? 20);

        return $this->sendResponse($likedPhotos, 'Liked photos retrieved successfully.');
    }
}
