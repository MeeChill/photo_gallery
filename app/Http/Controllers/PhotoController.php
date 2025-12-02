<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PhotoController extends Controller
{
    public function index(Request $request)
{
    $query = Photo::with(['user', 'likes', 'saves'])
        ->latest();

    if ($request->has('category') && $request->category != 'all') {
        $query->where('category', $request->category);
    }

    //Fitur Search
    if ($category = $request->input('category')) {
        if ($category !== 'all') {
            $query->where('category', $category);
        }
    }

    if($search = $request->input('search')){
       $query->where(function($q) use ($search) {
           $q->where('title', 'like', "%{$search}%")
             ->orWhere('description', 'like', "%{$search}%");
       });
    }

    $photos = $query->latest()->paginate(20);

    if ($request->ajax()) {
        return view('gallery.partials.photos', compact('photos'))->render();
    }

    $categories = Photo::select('category')->distinct()->pluck('category');

    return view('gallery.index', compact('photos', 'categories'));
}

    public function create()
    {
        return view('gallery.create');
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

    Photo::create([
        'user_id' => auth()->guard()->id(),
        'title' => $request->title,
        'description' => $request->description,
        'image_path' => 'photos/' . $imageName, // Path relatif ke public
        'category' => $request->category,
        'width' => $width, // Gunakan nilai yang didapat dari getimagesize
        'height' => $height, // Gunakan nilai yang didapat dari getimagesize
    ]);

    return redirect()->route('gallery.index')->with('success', 'Foto berhasil ditambahkan!');
}

    public function show(Photo $photo)
{
    // Load relasi untuk optimasi
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
        // Pastikan hanya pemilik foto yang bisa edit
        if (auth()->guard()->id() !== $photo->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('gallery.edit', compact('photo'));
    }

    public function update(Request $request, Photo $photo)
{
    // Pastikan hanya pemilik foto yang bisa update
    if (auth()->guard()->id() !== $photo->user_id) {
        abort(403, 'Unauthorized action.');
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

    // Jika ada gambar baru yang diupload
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

    return redirect()->route('gallery.show', $photo->id)->with('success', 'Foto berhasil diperbarui!');
}

    public function destroy(Photo $photo)
{
    // Pastikan hanya pemilik foto yang bisa hapus
    if (auth()->guard()->id() !== $photo->user_id) {
        abort(403, 'Unauthorized action.');
    }

    // Hapus gambar dari public folder
    if (file_exists(public_path($photo->image_path))) {
        unlink(public_path($photo->image_path));
    }

    // Hapus record dari database
    $photo->delete();

    return redirect()->route('gallery.index')->with('success', 'Foto berhasil dihapus!');
}

    public function like(Photo $photo)
    {
        $user = auth()->user(); // Hapus guard()

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
        $user = auth()->user(); // Hapus guard()

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
    // Increment download count
    $photo->increment('downloads');

    $filePath = public_path($photo->image_path);

    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }

    $fileName = $photo->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

    return response()->download($filePath, $fileName);
}

// report
public function report(Request $request, Photo $photo)
{
    $request->validate([
        'reason' => 'required|string|max:255',
        'message' => 'nullable|string|max:1000',
    ]);

    // Simpan laporan ke database
    \App\Models\Report::create([
        'user_id' => auth()->id(),
        'photo_id' => $photo->id,
        'reason' => $request->reason,
        'message' => $request->message,
    ]);

    return response()->json(['success' => true]);
}

}
