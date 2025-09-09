<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Photo;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->boards()->latest()->get();
        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        return view('boards.create');
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

        Board::create($data);

        return redirect()->route('boards.index')->with('success', 'Board created successfully!');
    }

    public function show(Board $board)
    {
        // Check if board is private and not owned by current user
        if ($board->is_private && $board->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $photos = $board->photos()->latest()->paginate(20);
        return view('boards.show', compact('board', 'photos'));
    }

    public function edit(Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('boards.edit', compact('board'));
    }

    public function update(Request $request, Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
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
            if ($board->cover_image) {
                unlink(public_path($board->cover_image));
            }

            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('board-covers'), $imageName);
            $data['cover_image'] = 'board-covers/' . $imageName;
        }

        $board->update($data);

        return redirect()->route('boards.show', $board)->with('success', 'Board updated successfully!');
    }

    public function destroy(Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus cover image jika ada
        if ($board->cover_image) {
            unlink(public_path($board->cover_image));
        }

        $board->delete();

        return redirect()->route('boards.index')->with('success', 'Board deleted successfully!');
    }

    public function addPhoto(Request $request, Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'photo_id' => 'required|exists:photos,id',
        ]);

        // Check if photo already in board
        if ($board->photos()->where('photo_id', $request->photo_id)->exists()) {
            return back()->with('error', 'Photo already in this board.');
        }

        $board->photos()->attach($request->photo_id);

        return back()->with('success', 'Photo added to board successfully!');
    }

    public function removePhoto(Request $request, Board $board, Photo $photo)
    {
        if ($board->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $board->photos()->detach($photo->id);

        return back()->with('success', 'Photo removed from board successfully!');
    }
}
