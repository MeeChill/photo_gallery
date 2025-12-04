<!-- resources/views/gallery/show.blade.php -->
@extends('layouts.app')

@section('title', $photo->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Photo Display -->
    <div class="lg:col-span-2">
        <div class="bg-dark-card rounded-lg shadow-lg border border-dark-border overflow-hidden">
            <img src="{{ asset($photo->image_path) }}"
                 alt="{{ $photo->title }}"
                 class="w-full h-auto">

            <!-- Photo Actions -->
            <div class="p-4 border-t border-dark-border">
                <div class="flex items-center flex-wrap gap-3">
                    <button onclick="toggleLike({{ $photo->id }})"
                            class="like-btn-{{ $photo->id }} flex items-center space-x-2 px-4 py-2 rounded-lg {{ $photo->isLiked() ? 'bg-red-900 text-red-200' : 'bg-dark-bg hover:bg-dark-border' }} transition min-w-[90px]">
                        <i class="fas fa-heart flex-shrink-0"></i>
                        <span class="like-count-{{ $photo->id }} min-w-[2ch] text-center">{{ $photo->likes()->count() }}</span>
                    </button>

                    <button onclick="toggleSave({{ $photo->id }})"
                            class="save-btn-{{ $photo->id }} flex items-center space-x-2 px-4 py-2 rounded-lg {{ $photo->isSaved() ? 'bg-purple-900 text-purple-200' : 'bg-dark-bg hover:bg-dark-border' }} transition min-w-[90px]">
                        <i class="fas fa-bookmark flex-shrink-0"></i>
                        <span class="save-count-{{ $photo->id }} min-w-[2ch] text-center">{{ $photo->saves()->count() }}</span>
                    </button>

                    <!-- Download Button -->
                    <a href="{{ route('photos.download', $photo->id) }}"
                       class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-dark-bg hover:bg-dark-border transition whitespace-nowrap"
                       title="Download Photo">
                        <i class="fas fa-download flex-shrink-0"></i>
                        <span>Download</span>
                    </a>

                    <!-- Share Button -->
                    <button onclick="openShareModal({{ $photo->id }})"
                            class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-dark-bg hover:bg-dark-border transition whitespace-nowrap"
                            title="Share Photo">
                        <i class="fas fa-share-alt flex-shrink-0"></i>
                        <span>Share</span>
                    </button>

                    <!-- Edit and Delete buttons for photo owner -->
                    @if (auth()->check() && auth()->id() == $photo->user_id)
                        <a href="{{ route('gallery.edit', $photo->id) }}"
                           class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition whitespace-nowrap">
                            <i class="fas fa-edit flex-shrink-0"></i>
                            <span>Edit</span>
                        </a>

                        <button onclick="confirmDelete({{ $photo->id }})"
                                class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 transition whitespace-nowrap">
                            <i class="fas fa-trash flex-shrink-0"></i>
                            <span>Delete</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Photos -->
        @if ($relatedPhotos->count() > 0)
            <div class="mt-6">
                <h3 class="text-xl font-semibold mb-4">Related Photos</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach ($relatedPhotos as $relatedPhoto)
                        <a href="{{ route('gallery.show', $relatedPhoto->id) }}" class="block">
                            <div class="bg-dark-card rounded-lg overflow-hidden shadow border border-dark-border">
                                <div class="w-full h-32 overflow-hidden">
                                    <img src="{{ asset($relatedPhoto->image_path) }}"
                                         alt="{{ $relatedPhoto->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="p-2">
                                    <p class="text-sm font-medium truncate">{{ $relatedPhoto->title }}</p>
                                    <p class="text-xs text-dark-muted truncate">by {{ $relatedPhoto->user->name }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Comments Section -->
        <div class="mt-8">
            <div class="bg-dark-card rounded-lg shadow-lg border border-dark-border p-6">
                <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
                    <h3 class="text-xl font-bold text-white whitespace-nowrap">
                        Komentar (<span id="commentCount">{{ $photo->comments->count() }}</span>)
                    </h3>
                    @if (auth()->check())
                        <button onclick="toggleCommentForm()"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 whitespace-nowrap flex-shrink-0">
                            <i class="fas fa-comment mr-2"></i>Tambah Komentar
                        </button>
                    @endif
                </div>

                <!-- Comment Form -->
                @if (auth()->check())
                    <div id="commentForm" class="hidden mb-6 p-4 bg-dark-bg rounded-lg">
                        <form id="commentFormElement" onsubmit="submitComment(event, {{ $photo->id }})">
                            @csrf
                            <div class="mb-4">
                                <textarea name="comment"
                                        id="commentInput"
                                        rows="3"
                                        class="w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                                        placeholder="Tulis komentar Anda..."
                                        required></textarea>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                        onclick="toggleCommentForm()"
                                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200 whitespace-nowrap">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 whitespace-nowrap">
                                    <i class="fas fa-paper-plane mr-2"></i>Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Comments List -->
                <div id="commentsList" class="space-y-4">
                    @foreach ($photo->comments as $comment)
                        <div class="flex space-x-3 p-4 bg-dark-bg rounded-lg" id="comment-{{ $comment->id }}">
                            <img src="{{ $comment->user->avatar ?: asset('images/default-avatar.png') }}"
                                alt="{{ $comment->user->name }}"
                                class="w-10 h-10 rounded-full flex-shrink-0 object-cover">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-white truncate">{{ $comment->user->name }}</h4>
                                        <p class="text-sm text-gray-400 whitespace-nowrap">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if (auth()->check() && (auth()->id() == $comment->user_id || auth()->id() == $photo->user_id))
                                        <div class="relative flex-shrink-0">
                                            <button onclick="toggleCommentMenu({{ $comment->id }})"
                                                    class="text-gray-400 hover:text-white p-2">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div id="commentMenu-{{ $comment->id }}"
                                                 class="hidden absolute right-0 mt-2 w-48 bg-dark-card rounded-lg shadow-lg border border-dark-border z-10">
                                                <button onclick="deleteComment({{ $photo->id }}, {{ $comment->id }})"
                                                        class="w-full text-left px-4 py-2 hover:bg-dark-bg transition whitespace-nowrap">
                                                    <i class="fas fa-trash mr-2 text-red-500"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-2 text-gray-300 break-words">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach

                    @if ($photo->comments()->count() == 0)
                        <div id="noComments" class="text-center py-8 text-gray-400">
                            <i class="fas fa-comments text-4xl mb-2"></i>
                            <p>Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Info - Sidebar -->
    <div class="lg:col-span-1">
        <div class="lg lg:top-6 space-y-6">
            <!-- Photo Title & Description -->
            <div class="bg-dark-card rounded-lg shadow-lg border border-dark-border p-6">
                <h1 class="text-2xl font-bold mb-4 break-words">{{ $photo->title }}</h1>

                @if ($photo->description)
                    <p class="text-dark-muted mb-6 break-words">{{ $photo->description }}</p>
                @endif

                <!-- Photo Stats -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-dark-bg rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-500 min-h-[2rem] flex items-center justify-center">
                            <span class="photo-likes-count">{{ $photo->likes()->count() }}</span>
                        </div>
                        <div class="text-sm text-dark-muted whitespace-nowrap">Likes</div>
                    </div>
                    <div class="bg-dark-bg rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-500 min-h-[2rem] flex items-center justify-center">
                            <span class="photo-downloads-count">{{ $photo->downloads }}</span>
                        </div>
                        <div class="text-sm text-dark-muted whitespace-nowrap">Downloads</div>
                    </div>
                </div>

                <!-- Photo Dimensions -->
                <div class="text-sm text-dark-muted flex items-center">
                    <i class="fas fa-expand-arrows-alt mr-2 flex-shrink-0"></i>
                    <span class="break-words">
                        @if ($photo->width && $photo->height)
                            {{ $photo->width }} × {{ $photo->height }} pixels
                        @else
                            Dimensions not available
                        @endif
                    </span>
                </div>
            </div>

            <!-- Photographer Info -->
            <div class="bg-dark-card rounded-lg shadow-lg border border-dark-border p-6">
                <h3 class="text-lg font-semibold mb-4">Photographer</h3>

                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ $photo->user->avatar ?: asset('images/default-avatar.png') }}"
                         alt="{{ $photo->user->name }}"
                         class="w-16 h-16 rounded-full flex-shrink-0 object-cover">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('profile.show', $photo->user) }}"
                           class="font-semibold hover:text-purple-400 transition block truncate">
                            {{ $photo->user->name }}
                        </a>
                        <p class="text-sm text-dark-muted whitespace-nowrap">
                            {{ $photo->user->photos->count() }} photos
                        </p>
                    </div>
                </div>

                @if ($photo->user->bio)
                    <p class="text-dark-muted text-sm break-words mb-4">{{ $photo->user->bio }}</p>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('profile.show', $photo->user) }}"
                       class="text-center px-4 py-2 bg-dark-bg rounded-lg hover:bg-dark-border transition whitespace-nowrap">
                        View Profile
                    </a>
                    <button class="px-4 py-2 bg-purple-600 rounded-lg hover:bg-purple-700 transition whitespace-nowrap">
                        Follow
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-dark-card rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Share Photo</h3>
                <button onclick="closeShareModal()" class="text-dark-muted hover:text-white p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Share Link</label>
                <div class="flex">
                    <input type="text" id="shareLink" readonly
                           class="flex-1 bg-dark-bg border border-dark-border rounded-l-lg px-4 py-2 focus:outline-none min-w-0">
                    <button onclick="copyShareLink()"
                            class="px-4 py-2 bg-purple-600 rounded-r-lg hover:bg-purple-700 transition flex-shrink-0">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Share to Social Media</label>
                <div class="flex space-x-3 justify-center">
                    <a href="#" id="shareFacebook" target="_blank"
                       class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center hover:bg-blue-700 transition flex-shrink-0">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" id="shareTwitter" target="_blank"
                       class="w-10 h-10 rounded-full bg-blue-400 flex items-center justify-center hover:bg-blue-500 transition flex-shrink-0">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" id="sharePinterest" target="_blank"
                       class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition flex-shrink-0">
                        <i class="fab fa-pinterest-p"></i>
                    </a>
                    <a href="#" id="shareWhatsApp" target="_blank"
                       class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center hover:bg-green-600 transition flex-shrink-0">
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

</div>
@endsection

@push('scripts')
<script>
    function toggleLike(photoId) {
        $.post(`/gallery/${photoId}/like`, function(data) {
            const likeBtn = $(`.like-btn-${photoId}`);
            const likeCount = $(`.like-count-${photoId}`);
            const photoLikesCount = $('.photo-likes-count');

            if (data.liked) {
                likeBtn.removeClass('bg-dark-bg hover:bg-dark-border').addClass('bg-red-900 text-red-200');
            } else {
                likeBtn.removeClass('bg-red-900 text-red-200').addClass('bg-dark-bg hover:bg-dark-border');
            }

            likeCount.text(data.count);
            photoLikesCount.text(data.count);
        });
    }

    function toggleSave(photoId) {
        $.post(`/gallery/${photoId}/save`, function(data) {
            const saveBtn = $(`.save-btn-${photoId}`);
            const saveCount = $(`.save-count-${photoId}`);

            if (data.saved) {
                saveBtn.removeClass('bg-dark-bg hover:bg-dark-border').addClass('bg-purple-900 text-purple-200');
            } else {
                saveBtn.removeClass('bg-purple-900 text-purple-200').addClass('bg-dark-bg hover:bg-dark-border');
            }

            saveCount.text(data.count);
        });
    }

    function confirmDelete(photoId) {
        document.getElementById('deleteForm').action = `/gallery/${photoId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
