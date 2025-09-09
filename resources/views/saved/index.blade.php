<!-- resources/views/saved/index.blade.php -->
@extends('layouts.app')

@section('title', 'Saved Photos')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold">Saved Photos</h1>
    <p class="text-dark-muted">Photos you've saved for later</p>
</div>

<!-- Save to Board Modal -->
<div id="saveToBoardModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-dark-card rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Save to Board</h3>
        <form id="saveToBoardForm" method="POST">
            @csrf
            <input type="hidden" name="photo_id" id="modalPhotoId">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Select Board</label>
                <select name="board_id" class="w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="">Choose a board...</option>
                    @foreach ($boards as $board)
                        <option value="{{ $board->id }}">{{ $board->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeSaveToBoardModal()" class="px-4 py-2 bg-dark-bg border border-dark-border rounded-lg hover:bg-dark-border transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@if ($savedPhotos->count() > 0)
    <div id="photo-container" class="masonry-grid">
        @foreach ($savedPhotos as $savedPhoto)
            <div class="masonry-item">
                <div class="photo-card bg-dark-card rounded-lg overflow-hidden shadow-lg border border-dark-border relative group">
                    <!-- Photo -->
                    <img src="{{ asset($savedPhoto->photo->image_path) }}"
                         alt="{{ $savedPhoto->photo->title }}"
                         class="w-full">

                    <!-- Overlay -->
                    <div class="photo-overlay absolute inset-0 flex flex-col justify-between p-4">
                        <!-- Top Actions -->
                        <div class="flex justify-between items-start">
                            <div class="action-buttons">
                                <button onclick="toggleLike({{ $savedPhoto->photo->id }})"
                                        class="like-btn-{{ $savedPhoto->photo->id }} w-10 h-10 rounded-full bg-black bg-opacity-50 flex items-center justify-center hover:bg-opacity-70 transition">
                                    <i class="fas fa-heart {{ $savedPhoto->photo->isLiked() ? 'text-red-500' : 'text-white' }}"></i>
                                </button>
                            </div>
                            <div class="action-buttons">
                                <button onclick="toggleSave({{ $savedPhoto->photo->id }})"
                                        class="save-btn-{{ $savedPhoto->photo->id }} w-10 h-10 rounded-full bg-black bg-opacity-50 flex items-center justify-center hover:bg-opacity-70 transition">
                                    <i class="fas fa-bookmark text-purple-500"></i>
                                </button>
                                <button onclick="openSaveToBoardModal({{ $savedPhoto->photo->id }})"
                                        class="w-10 h-10 rounded-full bg-black bg-opacity-50 flex items-center justify-center hover:bg-opacity-70 transition ml-2">
                                    <i class="fas fa-folder-plus text-white"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Bottom Info -->
                        <div class="action-buttons">
                            <a href="{{ route('gallery.show', $savedPhoto->photo->id) }}" class="text-white">
                                <h3 class="font-semibold text-lg mb-1">{{ $savedPhoto->photo->title }}</h3>
                                <div class="flex items-center text-sm">
                                    <img src="{{ $savedPhoto->photo->user->avatar ?: asset('images/default-avatar.png') }}"
                                         alt="{{ $savedPhoto->photo->user->name }}"
                                         class="w-6 h-6 rounded-full mr-2">
                                    <span>{{ $savedPhoto->photo->user->name }}</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 rounded-full px-3 py-1 text-sm flex items-center space-x-3">
                        <span class="like-count-{{ $savedPhoto->photo->id }}">
                            <i class="fas fa-heart text-red-500 mr-1"></i> {{ $savedPhoto->photo->likes()->count() }}
                        </span>
                        <span class="save-count-{{ $savedPhoto->photo->id }}">
                            <i class="fas fa-bookmark text-purple-500 mr-1"></i> {{ $savedPhoto->photo->saves()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $savedPhotos->links() }}
    </div>
@else
    <div class="text-center py-12">
        <i class="fas fa-bookmark text-6xl text-dark-muted mb-4"></i>
        <h3 class="text-xl font-semibold mb-2">No saved photos yet</h3>
        <p class="text-dark-muted mb-4">Save photos you like to find them easily later</p>
        <a href="{{ route('gallery.index') }}" class="px-6 py-3 bg-purple-600 rounded-lg hover:bg-purple-700 transition inline-block">
            <i class="fas fa-images mr-2"></i>Browse Gallery
        </a>
    </div>
@endif
@endsection

@push('scripts')
<script>
    function openSaveToBoardModal(photoId) {
        document.getElementById('modalPhotoId').value = photoId;
        document.getElementById('saveToBoardModal').classList.remove('hidden');
    }

    function closeSaveToBoardModal() {
        document.getElementById('saveToBoardModal').classList.add('hidden');
    }

    document.getElementById('saveToBoardForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const photoId = formData.get('photo_id');

        fetch(`/photos/${photoId}/save-to-board`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeSaveToBoardModal();
                alert('Photo saved to board successfully!');
            } else {
                alert(data.message || 'Error saving photo to board');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving photo to board');
        });
    });
</script>
@endpush
