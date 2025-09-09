<!-- resources/views/boards/create.blade.php -->
@extends('layouts.app')

@section('title', 'Create Board')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-dark-card rounded-lg shadow-lg border border-dark-border p-6">
        <h2 class="text-2xl font-bold mb-6">Create New Board</h2>

        <form action="{{ route('boards.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Cover Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Cover Image (Optional)</label>
                <div class="border-2 border-dashed border-dark-border rounded-lg p-8 text-center hover:border-purple-500 transition cursor-pointer" id="drop-zone">
                    <input type="file" name="cover_image" id="cover-input" class="hidden" accept="image/*">
                    <div id="upload-preview">
                        <i class="fas fa-cloud-upload-alt text-4xl text-dark-muted mb-3"></i>
                        <p class="text-dark-muted">Click to browse or drag & drop cover image</p>
                        <p class="text-sm text-dark-muted mt-2">JPG, PNG, GIF up to 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">Board Name</label>
                <input type="text" id="name" name="name"
                       class="w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                       value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">Description (Optional)</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Privacy -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_private" value="1" class="mr-2">
                    <span>Make this board private</span>
                </label>
                <p class="text-sm text-dark-muted mt-1">Only you can view this board</p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('boards.index') }}" class="px-6 py-2 bg-dark-bg border border-dark-border rounded-lg hover:bg-dark-border transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 rounded-lg hover:bg-purple-700 transition">
                    <i class="fas fa-save mr-2"></i>Create Board
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const dropZone = document.getElementById('drop-zone');
    const coverInput = document.getElementById('cover-input');
    const uploadPreview = document.getElementById('upload-preview');

    dropZone.addEventListener('click', () => coverInput.click());

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
            coverInput.files = e.dataTransfer.files;
            handleImageSelect(e.dataTransfer.files[0]);
        }
    });

    coverInput.addEventListener('change', (e) => {
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
</script>
@endpush
