<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Save;
use App\Models\Board;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function toggle(Photo $photo)
    {
        $user = auth()->user();

        // Check if user already saved the photo
        $existingSave = Save::where('user_id', $user->id)
                            ->where('photo_id', $photo->id)
                            ->first();

        if ($existingSave) {
            // Unsave
            $existingSave->delete();
            $saved = false;
        } else {
            // Save
            Save::create([
                'user_id' => $user->id,
                'photo_id' => $photo->id
            ]);
            $saved = true;
        }

        $saveCount = Save::where('photo_id', $photo->id)->count();

        return response()->json([
            'saved' => $saved,
            'count' => $saveCount
        ]);
    }

    public function index()
    {
        $savedPhotos = Save::where('user_id', auth()->id())
            ->with('photo.user')
            ->latest()
            ->paginate(20);

        $boards = auth()->user()->boards()->latest()->get();

        return view('saved.index', compact('savedPhotos', 'boards'));
    }

    public function saveToBoard(Request $request, Photo $photo)
    {
        $request->validate([
            'board_id' => 'required|exists:boards,id',
        ]);

        $board = Board::find($request->board_id);

        if ($board->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if photo already in board
        if ($board->photos()->where('photo_id', $photo->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Photo already in this board.'
            ]);
        }

        $board->photos()->attach($photo->id);

        return response()->json([
            'success' => true,
            'message' => 'Photo added to board successfully!'
        ]);
    }
}
