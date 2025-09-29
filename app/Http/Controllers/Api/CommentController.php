<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Comment;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends BaseController
{
    /**
     * Get comments for a specific photo
     */
    public function index(Request $request, $photoId)
    {
        try {
            $photo = Photo::findOrFail($photoId);

            $comments = $photo->comments()
                ->with('user')
                ->latest()
                ->paginate($request->per_page ?? 10);

            return $this->sendResponse($comments, 'Comments retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Photo not found.', [], 404);
        }
    }

    /**
     * Add a new comment to a photo
     */
    public function store(Request $request, $photoId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            $photo = Photo::findOrFail($photoId);

            $comment = new Comment();
            $comment->user_id = auth()->id();
            $comment->photo_id = $photo->id;
            $comment->comment = $request->comment;
            $comment->save();

            // Load user relationship
            $comment->load('user');

            return $this->sendResponse($comment, 'Comment added successfully.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to add comment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update a comment
     */
    public function update(Request $request, $photoId, $commentId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comment' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            $comment = Comment::findOrFail($commentId);

            // Check if user is the comment owner
            if ($comment->user_id !== auth()->id()) {
                return $this->sendError('Unauthorized action.', [], 403);
            }

            $comment->comment = $request->comment;
            $comment->save();

            // Load user relationship
            $comment->load('user');

            return $this->sendResponse($comment, 'Comment updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to update comment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy($photoId, $commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);

            // Check if user is the comment owner or photo owner
            if ($comment->user_id !== auth()->id() && $comment->photo->user_id !== auth()->id()) {
                return $this->sendError('Unauthorized action.', [], 403);
            }

            $comment->delete();

            return $this->sendResponse([], 'Comment deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete comment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get comments by user
     */
    public function userComments(Request $request)
    {
        try {
            $comments = Comment::where('user_id', auth()->id())
                ->with(['photo', 'photo.user'])
                ->latest()
                ->paginate($request->per_page ?? 10);

            return $this->sendResponse($comments, 'User comments retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve user comments: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get recent comments across all photos
     */
    public function recent(Request $request)
    {
        try {
            $comments = Comment::with(['user', 'photo'])
                ->latest()
                ->paginate($request->per_page ?? 20);

            return $this->sendResponse($comments, 'Recent comments retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve recent comments: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search comments
     */
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }

            $comments = Comment::with(['user', 'photo'])
                ->where('comment', 'like', '%' . $request->query . '%')
                ->latest()
                ->paginate($request->per_page ?? 20);

            return $this->sendResponse($comments, 'Search results retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to search comments: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get comment statistics
     */
    public function stats()
    {
        try {
            $stats = [
                'total_comments' => Comment::count(),
                'comments_today' => Comment::whereDate('created_at', now())->count(),
                'comments_this_week' => Comment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'comments_this_month' => Comment::whereMonth('created_at', now()->month)->count(),
                'top_commenters' => Comment::select('user_id', \DB::raw('count(*) as total'))
                    ->groupBy('user_id')
                    ->with('user')
                    ->orderBy('total', 'desc')
                    ->take(5)
                    ->get(),
                'most_commented_photos' => Photo::withCount('comments')
                    ->orderBy('comments_count', 'desc')
                    ->take(5)
                    ->get()
            ];

            return $this->sendResponse($stats, 'Comment statistics retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve comment statistics: ' . $e->getMessage(), 500);
        }
    }
}
