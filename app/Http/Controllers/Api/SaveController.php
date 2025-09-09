<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Photo;
use App\Models\Board;
use Illuminate\Http\Request;

class SaveController extends BaseController
{
    public function toggle(Request $request, $photoId)
    {
        $photo = Photo::findOrFail($photoId);
        $user = auth()->user();

        if ($photo->isSaved()) {
            $photo->saves()->detach($user->id);
            $saved = false;
            $message = 'Photo unsaved successfully';
        } else {
            $photo->saves()->attach($user->id);
            $saved = true;
            $message = 'Photo saved successfully';
        }

        return $this->sendResponse([
            'saved' => $saved,
            'count' => $photo->saves()->count()
        ], $message);
    }

    public function saveToBoard(Request $request, $photoId)
    {
        $request->validate([
            'board_id' => 'required|exists:boards,id',
        ]);

        $photo = Photo::findOrFail($photoId);
        $board = Board::find($request->board_id);

        if ($board->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        // Check if photo already in board
        if ($board->photos()->where('photo_id', $photoId)->exists()) {
            return $this->sendError('Photo already in this board.', [], 400);
        }

        $board->photos()->attach($photoId);

        return $this->sendResponse([], 'Photo added to board successfully.');
    }

    public function userSaves(Request $request)
    {
        $user = auth()->user();

        $savedPhotos = $user->savedPhotos()
            ->with('photo.user')
            ->latest()
            ->paginate($request->per_page ?? 20);

        return $this->sendResponse($savedPhotos, 'Saved photos retrieved successfully.');
    }
}
