<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Photo;
use App\Models\Board;
use App\Models\Like;
use App\Models\Save;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        // Statistics
        $totalUsers = User::count();
        $totalPhotos = Photo::count();
        $totalBoards = Board::count();
        $totalLikes = Like::count();
        $totalSaves = Save::count();

        // Recent users
        $recentUsers = User::latest()->take(5)->get();

        // Recent photos
        $recentPhotos = Photo::with('user')->latest()->take(5)->get();

        // Most liked photos
        $mostLikedPhotos = Photo::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();

        // Most saved photos
        $mostSavedPhotos = Photo::withCount('saves')
            ->orderBy('saves_count', 'desc')
            ->take(5)
            ->get();

        // User registration chart data (last 7 days)
        $userRegistrationData = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Photo upload chart data (last 7 days)
        $photoUploadData = Photo::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Category distribution
        $categoryDistribution = Photo::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPhotos',
            'totalBoards',
            'totalLikes',
            'totalSaves',
            'recentUsers',
            'recentPhotos',
            'mostLikedPhotos',
            'mostSavedPhotos',
            'userRegistrationData',
            'photoUploadData',
            'categoryDistribution'
        ));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function photos()
    {
        $photos = Photo::with('user')->latest()->paginate(10);
        return view('admin.photos', compact('photos'));
    }

    public function boards()
    {
        $boards = Board::with('user', 'photos')->latest()->paginate(10);
        return view('admin.boards', compact('boards'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'User status updated successfully!');
    }

    public function togglePhotoFeatured(Photo $photo)
    {
        $photo->is_featured = !$photo->is_featured;
        $photo->save();

        return back()->with('success', 'Photo featured status updated successfully!');
    }
}
