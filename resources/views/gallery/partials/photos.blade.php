<!-- resources/views/gallery/partials/photos.blade.php -->
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
                        <button onclick="toggleSave({{ $photo->id }})"
                                class="save-btn-{{ $photo->id }} w-10 h-10 rounded-full bg-black bg-opacity-50 flex items-center justify-center hover:bg-opacity-70 transition">
                            <i class="fas fa-bookmark {{ $photo->isSaved() ? 'text-purple-500' : 'text-white' }}"></i>
                        </button>
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
        </div>
    </div>
@endforeach

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
