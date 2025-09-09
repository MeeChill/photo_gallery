<!-- resources/views/gallery/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Photo')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-dark-card rounded-lg shadow-lg border border-dark-border p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Photo</h2>

        <form action="{{ route('gallery.update', $photo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Current Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Current Photo</label>
                <div class="border border-dark-border rounded-lg p-4">
                    <img src="{{ Storage::url($photo->image_path) }}"
                         alt="{{ $photo->title }}"
                         class="max-h-64 mx-auto rounded-lg">
                </div>
            </div>

            <!-- New Image Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Replace Image (Optional)</label>
                <div class="border-2 border-dashed border-dark-border rounded-lg p-8 text-center hover:border-purple-500 transition cursor-pointer" id="drop-zone">
                    <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                    <div id="upload-preview">
                        <i class="fas fa-cloud-upload-alt text-4xl text-dark-muted mb-3"></i>
                        <p class="text-dark-muted">Click to browse or drag & drop new image</p>
                        <p class="text-sm text-dark-muted mt-2">JPG, PNG, GIF up to 5MB</p>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium mb-2">Title</label>
                <input type="text" id="title" name="title"
                       class="w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                       value="{{ old('title', $photo->title) }}" required>
                @error('title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description', $photo->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium mb-2">Category</label>
                <select id="category" name="category"
                        class="w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                    <option value="nature" {{ $photo->category == 'nature' ? 'selected' : '' }}>Nature</option>
                    <option value="architecture" {{ $photo->category == 'architecture' ? 'selected' : '' }}>Architecture</option>
                    <option value="people" {{ $photo->category == 'people' ? 'selected' : '' }}>People</option>
                    <option value="technology" {{ $photo->category == 'technology' ? 'selected' : '' }}>Technology</option>
                    <option value="animals" {{ $photo->category == 'animals' ? 'selected' : '' }}>Animals</option>
                    <option value="food" {{ $photo->category == 'food' ? 'selected' : '' }}>Food</option>
                    <option value="travel" {{ $photo->category == 'travel' ? 'selected' : '' }}>Travel</option>
                    <option value="art" {{ $photo->category == 'art' ? 'selected' : '' }}>Art</option>
                    <option value="general" {{ $photo->category == 'general' ? 'selected' : '' }}>General</option>
                </select>
                @error('category')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-between">
                <a href="{{ route('gallery.show', $photo->id) }}" class="px-6 py-2 bg-dark-bg border border-dark-border rounded-lg hover:bg-dark-border transition">
                    Cancel
                </a>
                <div class="space-x-3">
                    <button type="button" onclick="confirmDelete()" class="px-6 py-2 bg-red-600 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                    <button type="submit" class="px-6 py-2 bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-dark-card rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Confirm Delete</h3>
        <p class="text-dark-muted mb-6">Are you sure you want to delete this photo? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-dark-bg border border-dark-border rounded-lg hover:bg-dark-border transition">
                Cancel
            </button>
            <form action="{{ route('gallery.destroy', $photo->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 rounded-lg hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const dropZone = document.getElementById('drop-zone');
    const imageInput = document.getElementById('image-input');
    const uploadPreview = document.getElementById('upload-preview');

    dropZone.addEventListener('click', () => imageInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-purple-500');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-purple-500');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-purple-500');

        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            handleImageSelect(e.dataTransfer.files[0]);
        }
    });

    imageInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleImageSelect(e.target.files[0]);
        }
    });

    function handleImageSelect(file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            uploadPreview.innerHTML = `
                <img src="${e.target.result}" class="max-h-64 mx-auto rounded-lg mb-3">
                <p class="text-dark-muted">${file.name}</p>
                <p class="text-sm text-dark-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
            `;
        };
        reader.readAsDataURL(file);
    }

    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
