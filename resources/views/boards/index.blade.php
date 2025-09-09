<!-- resources/views/boards/index.blade.php -->
@extends('layouts.app')

@section('title', 'My Boards')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">My Boards</h1>
            <p class="text-dark-muted">Organize your saved photos into collections</p>
        </div>
        <a href="{{ route('boards.create') }}" class="px-6 py-3 bg-purple-600 rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-plus mr-2"></i>Create Board
        </a>
    </div>
</div>

@if ($boards->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($boards as $board)
            <div class="bg-dark-card rounded-lg overflow-hidden shadow-lg border border-dark-border hover:shadow-xl transition">
                <a href="{{ route('boards.show', $board) }}">
                    @if ($board->cover_image)
                        <img src="{{ asset($board->cover_image) }}" alt="{{ $board->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-dark-bg flex items-center justify-center">
                            <i class="fas fa-folder text-4xl text-dark-muted"></i>
                        </div>
                    @endif
                </a>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-lg">{{ $board->name }}</h3>
                        @if ($board->is_private)
                            <i class="fas fa-lock text-dark-muted" title="Private Board"></i>
                        @endif
                    </div>
                    <p class="text-dark-muted text-sm mb-3">{{ $board->photos()->count() }} photos</p>
                    <div class="flex justify-between items-center">
                        <a href="{{ route('boards.edit', $board) }}" class="text-purple-400 hover:text-purple-300">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('boards.destroy', $board) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12">
        <i class="fas fa-folder-open text-6xl text-dark-muted mb-4"></i>
        <h3 class="text-xl font-semibold mb-2">No boards yet</h3>
        <p class="text-dark-muted mb-4">Create boards to organize your saved photos</p>
        <a href="{{ route('boards.create') }}" class="px-6 py-3 bg-purple-600 rounded-lg hover:bg-purple-700 transition inline-block">
            <i class="fas fa-plus mr-2"></i>Create Your First Board
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
