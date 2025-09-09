<!-- resources/views/boards/show.blade.php -->
@extends('layouts.app')

@section('title', $board->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold">{{ $board->name }}</h1>
            <p class="text-dark-muted">{{ $board->description }}</p>
            <div class="flex items-center mt-2">
                <img src="{{ $board->user->avatar ?: asset('images/default-avatar.png') }}"
                     alt="{{ $board->user->name }}"
                     class="w-6 h-6 rounded-full mr-2">
                <span>by {{ $board->user->name }}</span>
                @if ($board->is_private)
                    <span class="ml-3 text-sm bg-dark-bg px-2 py-1 rounded">
                        <i class="fas fa-lock mr-1"></i>Private
                    </span>
                @endif
            </div>
        </div>
        @if (auth()->check() && auth()->id() == $board->user_id)
            <div class="flex space-x-2">
                <a href="{{ route('boards.edit', $board) }}" class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('boards.destroy', $board) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@if ($photos->count() > 0)
    <div class="masonry-grid">
        @foreach ($photos as $photo)
            <div class="masonry-item">
                <div class="photo-card bg-dark-card rounded-lg overflow-hidden shadow-lg border border-dark-border relative group">
                    <!-- Photo -->
                    <img src="{{ asset($photo->image_path) }}"
                         alt="{{ $photo->title }}"
                         class="w-full">

                    <!-- Overlay -->
                    <div class="photo-overlay absolute inset-0 flex flex-col justify-between p-4">
                        <!-- Top Actions -->
                        <div class="flex justify-between items-start">
                            <div class="action-buttons">
                                <button onclick="toggleLike({{ $photo->id }})"
                                        class="like-btn-{{ $photo->id }} w-10 h-10 rounded-full bg-black bg-opacity-50 flex items-center justify-center hover:bg-opacity-70 transition">
                                    <i class="fas fa-heart {{ $photo->isLiked() ? 'text-red-500' : 'text-white' }}"></i>
                                </button>
                            </div>
                            <div class="action-buttons">
                                @if (auth()->check() && auth()->id() == $board->user_id)
                                    <form action="{{ route('boards.removePhoto', [$board, $photo]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 rounded-full bg-red-600 bg-opacity-80 flex items-center justify-center hover:bg-opacity-100 transition">
                                            <i class="fas fa-times text-white"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="action-buttons">
                                <!-- Download Button -->
                                <a href="{{ route('photos.download', $photo->id) }}"
                                class="w-10 h-10 rounded-full bg-black bg-opacity-50 flex items-center justify-center hover:bg-opacity-70 transition ml-2"
                                title="Download Photo">
                                    <i class="fas fa-download text-white"></i>
                                </a>
                            </div>
                            <div class="action-buttons">
                                <!-- Share Button -->
                                <button onclick="openShareModal({{ $photo->id }})"
                                        class="w-10 h-10 rounded-full bg-black bg-opacity-50 flex items-center justify-center hover:bg-opacity-70 transition ml-2"
                                        title="Share Photo">
                                    <i class="fas fa-share-alt text-white"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Bottom Info -->
                        <div class="action-buttons">
                            <a href="{{ route('gallery.show', $photo->id) }}" class="text-white">
                                <h3 class="font-semibold text-lg mb-1">{{ $photo->title }}</h3>
                                <div class="flex items-center text-sm">
                                    <img src="{{ $photo->user->avatar ?: asset('images/default-avatar.png') }}"
                                         alt="{{ $photo->user->name }}"
                                         class="w-6 h-6 rounded-full mr-2">
                                    <span>{{ $photo->user->name }}</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 rounded-full px-3 py-1 text-sm flex items-center space-x-3">
                        <span class="like-count-{{ $photo->id }}">
                            <i class="fas fa-heart text-red-500 mr-1"></i> {{ $photo->likes()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $photos->links() }}
    </div>
@else
    <div class="text-center py-12">
        <i class="fas fa-images text-6xl text-dark-muted mb-4"></i>
        <h3 class="text-xl font-semibold mb-2">No photos in this board yet</h3>
        <p class="text-dark-muted mb-4">Add photos to this board to organize them</p>
        <a href="{{ route('gallery.index') }}" class="px-6 py-3 bg-purple-600 rounded-lg hover:bg-purple-700 transition inline-block">
            <i class="fas fa-plus mr-2"></i>Add Photos
        </a>
    </div>
@endif

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-dark-card rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Share Photo</h3>
            <button onclick="closeShareModal()" class="text-dark-muted hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Share Link</label>
            <div class="flex">
                <input type="text" id="shareLink" readonly
                       class="flex-1 bg-dark-bg border border-dark-border rounded-l-lg px-4 py-2 focus:outline-none">
                <button onclick="copyShareLink()"
                        class="px-4 py-2 bg-purple-600 rounded-r-lg hover:bg-purple-700 transition">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Share to Social Media</label>
            <div class="flex space-x-3">
                <a href="#" id="shareFacebook" target="_blank"
                   class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center hover:bg-blue-700 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" id="shareTwitter" target="_blank"
                   class="w-10 h-10 rounded-full bg-blue-400 flex items-center justify-center hover:bg-blue-500 transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" id="sharePinterest" target="_blank"
                   class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition">
                    <i class="fab fa-pinterest-p"></i>
                </a>
                <a href="#" id="shareWhatsApp" target="_blank"
                   class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center hover:bg-green-600 transition">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>

        <div class="text-center">
            <button onclick="closeShareModal()"
                    class="px-6 py-2 bg-dark-bg border border-dark-border rounded-lg hover:bg-dark-border transition">
                Close
            </button>
        </div>
    </div>
</div>

@endsection
