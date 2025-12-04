<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::with(['user', 'likes', 'saves'])->latest();

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->ajax()) {
            $photos = $query->paginate(20);
            return view('gallery.partials.photos', compact('photos'))->render();
        }

        $photos = $query->paginate(20);
        $categories = Photo::distinct()->pluck('category');

        return view('gallery.index', compact('photos', 'categories'));
    }

    public function create()
    {
        return view('gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();

        // dapatkan ukuran gambar
        $imgInfo = getimagesize($image->getPathname());
        $width = $imgInfo[0];
        $height = $imgInfo[1];

        // simpan ke /public/photos
        $image->move(public_path('photos'), $imageName);

        Photo::create([
            'user_id'     => auth()->id(),  // FIX UTAMA
            'title'       => $request->title,
            'description' => $request->description,
            'image_path'  => 'photos/' . $imageName,
            'category'    => $request->category,
            'width'       => $width,
            'height'      => $height,
        ]);

        return redirect()->route('gallery.index')->with('success', 'Foto berhasil ditambahkan!');
    }

    public function show(Photo $photo)
    {
        $photo->load(['user', 'likes', 'saves']);

        $relatedPhotos = Photo::where('category', $photo->category)
            ->where('id', '!=', $photo->id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('gallery.show', compact('photo', 'relatedPhotos'));
    }

    public function edit(Photo $photo)
    {
        if (auth()->id() !== $photo->user_id) {
            abort(403);
        }

        return view('gallery.edit', compact('photo'));
    }

    public function update(Request $request, Photo $photo)
    {
        if (auth()->id() !== $photo->user_id) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = [
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
        ];

        if ($request->hasFile('image')) {

            if (file_exists(public_path($photo->image_path))) {
                unlink(public_path($photo->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();

            // ukuran baru
            $imgInfo = getimagesize($image->getPathname());
            $data['width']  = $imgInfo[0];
            $data['height'] = $imgInfo[1];

            $image->move(public_path('photos'), $imageName);
            $data['image_path'] = 'photos/' . $imageName;
        }

        $photo->update($data);

        return redirect()->route('gallery.show', $photo->id)->with('success', 'Foto berhasil diperbarui!');
    }

    public function destroy(Photo $photo)
    {
        if (auth()->id() !== $photo->user_id) {
            abort(403);
        }

        if (file_exists(public_path($photo->image_path))) {
            unlink(public_path($photo->image_path));
        }

        $photo->delete();

        return redirect()->route('gallery.index')->with('success', 'Foto berhasil dihapus!');
    }

    public function like(Photo $photo)
    {
        $user = auth()->user();

        if ($photo->isLiked()) {
            $photo->likes()->detach($user->id);
            $liked = false;
        } else {
            $photo->likes()->attach($user->id);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $photo->likes()->count()
        ]);
    }

    public function save(Photo $photo)
    {
        $user = auth()->user();

        if ($photo->isSaved()) {
            $photo->saves()->detach($user->id);
            $saved = false;
        } else {
            $photo->saves()->attach($user->id);
            $saved = true;
        }

        return response()->json([
            'saved' => $saved,
            'count' => $photo->saves()->count()
        ]);
    }

    public function download(Photo $photo)
    {
        $photo->increment('downloads');

        $filePath = public_path($photo->image_path);
        if (!file_exists($filePath)) {
            abort(404);
        }

        $fileName = $photo->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        return response()->download($filePath, $fileName);
    }
}
