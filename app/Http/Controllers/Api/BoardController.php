<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Board;
use App\Models\Photo;
use Illuminate\Http\Request;

class BoardController extends BaseController
{
    public function index(Request $request)
    {
        $boards = auth()->user()->boards()
            ->withCount('photos')
            ->latest()
            ->paginate($request->per_page ?? 20);

        return $this->sendResponse($boards, 'Boards retrieved successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_private' => 'boolean',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'is_private' => $request->is_private ?? false,
        ];

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('board-covers'), $imageName);
            $data['cover_image'] = 'board-covers/' . $imageName;
        }

        $board = Board::create($data);

        return $this->sendResponse($board, 'Board created successfully.', 201);
    }

    public function show($id)
    {
        $board = Board::with(['user', 'photos.user'])->findOrFail($id);

        // Check if board is private and not owned by current user
        if ($board->is_private && $board->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        return $this->sendResponse($board, 'Board retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        if ($board->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_private' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'is_private' => $request->is_private ?? false,
        ];

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama jika ada
            if ($board->cover_image && file_exists(public_path($board->cover_image))) {
                unlink(public_path($board->cover_image));
            }

            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('board-covers'), $imageName);
            $data['cover_image'] = 'board-covers/' . $imageName;
        }

        $board->update($data);

        return $this->sendResponse($board, 'Board updated successfully.');
    }

    public function destroy($id)
    {
        $board = Board::findOrFail($id);

        if ($board->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        // Hapus cover image jika ada
        if ($board->cover_image && file_exists(public_path($board->cover_image))) {
            unlink(public_path($board->cover_image));
        }

        $board->delete();

        return $this->sendResponse([], 'Board deleted successfully.');
    }

    public function addPhoto(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        if ($board->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        $request->validate([
            'photo_id' => 'required|exists:photos,id',
        ]);

        // Check if photo already in board
        if ($board->photos()->where('photo_id', $request->photo_id)->exists()) {
            return $this->sendError('Photo already in this board.', [], 400);
        }

        $board->photos()->attach($request->photo_id);

        return $this->sendResponse([], 'Photo added to board successfully.');
    }

    public function removePhoto($id, $photoId)
    {
        $board = Board::findOrFail($id);

        if ($board->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        $board->photos()->detach($photoId);

        return $this->sendResponse([], 'Photo removed from board successfully.');
    }

    public function photos($id)
    {
        $board = Board::findOrFail($id);

        // Check if board is private and not owned by current user
        if ($board->is_private && $board->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        $photos = $board->photos()
            ->with('user')
            ->latest()
            ->paginate(request()->per_page ?? 20);

        return $this->sendResponse($photos, 'Board photos retrieved successfully.');
    }
}
