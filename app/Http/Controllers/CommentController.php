<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, Photo $photo)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'comment' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buat komentar baru
            $comment = new Comment();
            $comment->user_id = auth()->id();
            $comment->photo_id = $photo->id;
            $comment->comment = $request->comment;
            $comment->save();

            // Load relasi user
            $comment->load('user');

            return response()->json([
                'success' => true,
                'comment' => $comment,
                'comments_count' => $photo->comments()->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Photo $photo, Comment $comment)
    {
        try {
            // Check if user is the comment owner or photo owner
            if (auth()->id() !== $comment->user_id && auth()->id() !== $photo->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'comments_count' => $photo->comments()->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment: ' . $e->getMessage()
            ], 500);
        }
    }
}
