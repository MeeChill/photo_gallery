<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PhotoController extends BaseController
{
    public function index(Request $request)
    {
        $query = Photo::with(['user', 'likes', 'saves'])
            ->latest();

        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        $photos = $query->paginate($request->per_page ?? 20);

        return $this->sendResponse($photos, 'Photos retrieved successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'category' => 'required|string',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();

        // Get image dimensions
        $imageInfo = getimagesize($image->getPathname());
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Simpan ke public/photos
        $image->move(public_path('photos'), $imageName);

        $photo = Photo::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => 'photos/' . $imageName,
            'category' => $request->category,
            'width' => $width,
            'height' => $height,
        ]);

        return $this->sendResponse($photo, 'Photo created successfully.', 201);
    }

    public function show($id)
    {
        $photo = Photo::with(['user', 'likes', 'saves'])->findOrFail($id);

        return $this->sendResponse($photo, 'Photo retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $photo = Photo::findOrFail($id);

        if ($photo->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'category' => 'required|string',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if (file_exists(public_path($photo->image_path))) {
                unlink(public_path($photo->image_path));
            }

            // Upload gambar baru
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();

            // Get image dimensions
            $imageInfo = getimagesize($image->getPathname());
            $width = $imageInfo[0];
            $height = $imageInfo[1];

            // Simpan gambar baru
            $image->move(public_path('photos'), $imageName);

            $data['image_path'] = 'photos/' . $imageName;
            $data['width'] = $width;
            $data['height'] = $height;
        }

        $photo->update($data);

        return $this->sendResponse($photo, 'Photo updated successfully.');
    }

    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        if ($photo->user_id !== auth()->id()) {
            return $this->sendError('Unauthorized action.', [], 403);
        }

        // Hapus gambar dari public folder
        if (file_exists(public_path($photo->image_path))) {
            unlink(public_path($photo->image_path));
        }

        $photo->delete();

        return $this->sendResponse([], 'Photo deleted successfully.');
    }

    public function download($id)
    {
        $photo = Photo::findOrFail($id);

        // Increment download count
        $photo->increment('downloads');

        $filePath = public_path($photo->image_path);

        if (!file_exists($filePath)) {
            return $this->sendError('File not found.', [], 404);
        }

        $fileName = $photo->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }
}
