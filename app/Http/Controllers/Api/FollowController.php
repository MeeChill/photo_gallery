<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FollowController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Follow a user
     */
    public function follow(User $user): JsonResponse
    {
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself'
            ], 400);
        }

        $existingFollow = Follow::where('follower_id', auth()->id())
                                ->where('following_id', $user->id)
                                ->first();

        if ($existingFollow) {
            return response()->json([
                'success' => false,
                'message' => 'You are already following this user'
            ], 400);
        }

        Follow::create([
            'follower_id' => auth()->id(),
            'following_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User followed successfully',
            'data' => [
                'is_following' => true,
                'followers_count' => $user->followersCount(),
                'following_count' => auth()->user()->followingCount()
            ]
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user): JsonResponse
    {
        $follow = Follow::where('follower_id', auth()->id())
                        ->where('following_id', $user->id)
                        ->first();

        if (!$follow) {
            return response()->json([
                'success' => false,
                'message' => 'You are not following this user'
            ], 400);
        }

        $follow->delete();

        return response()->json([
            'success' => true,
            'message' => 'User unfollowed successfully',
            'data' => [
                'is_following' => false,
                'followers_count' => $user->followersCount(),
                'following_count' => auth()->user()->followingCount()
            ]
        ]);
    }

    /**
     * Get user's followers
     */
    public function followers(User $user): JsonResponse
    {
        $followers = $user->followers()
            ->with('follower')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $followers
        ]);
    }

    /**
     * Get user's following
     */
    public function following(User $user): JsonResponse
    {
        $following = $user->following()
            ->with('following')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $following
        ]);
    }

    /**
     * Check if following a user
     */
    public function checkFollow(User $user): JsonResponse
    {
        $isFollowing = auth()->user()->isFollowing($user);

        return response()->json([
            'success' => true,
            'data' => [
                'is_following' => $isFollowing,
                'followers_count' => $user->followersCount(),
                'following_count' => $user->followingCount()
            ]
        ]);
    }

    /**
     * Get current user's follow suggestions
     */
    public function suggestions(): JsonResponse
    {
        $authUser = auth()->user();

        // Get users that are not followed by current user and not the user themselves
        $suggestions = User::where('id', '!=', $authUser->id)
            ->whereNotIn('id', function($query) use ($authUser) {
                $query->select('following_id')
                    ->from('follows')
                    ->where('follower_id', $authUser->id);
            })
            ->withCount(['followers', 'photos'])
            ->orderBy('followers_count', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }
}
