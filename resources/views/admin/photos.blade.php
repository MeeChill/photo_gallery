// resources/views/admin/photos.blade.php
@extends('layouts.app')

@section('title', 'Manage Photos')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Manage Photos</h1>
    <p class="text-dark-muted">View and manage all uploaded photos</p>
</div>

<div class="bg-dark-card rounded-lg border border-dark-border">
    <div class="p-6 border-b border-dark-border">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-bold">All Photos</h2>
            <div class="mt-4 sm:mt-0">
                <div class="relative">
                    <input type="text" placeholder="Search photos..." class="bg-dark-bg border border-dark-border rounded-lg py-2 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500 w-full sm:w-64">
                    <i class="fas fa-search absolute right-3 top-3 text-dark-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-dark-bg text-left">
                    <th class="p-4">Photo</th>
                    <th class="p-4">Uploader</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Stats</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($photos as $photo)
                    <tr class="border-b border-dark-border hover:bg-dark-bg transition">
                        <td class="p-4">
                            <div class="flex items-center">
                                <img src="{{ asset($photo->image_path) }}"
                                     alt="{{ $photo->title }}"
                                     class="w-16 h-16 object-cover rounded mr-3">
                                <div>
                                    <p class="font-medium max-w-xs truncate">{{ $photo->title }}</p>
                                    <p class="text-sm text-dark-muted">{{ $photo->created_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center">
                                <img src="{{ $photo->user->avatar ?: asset('images/default-avatar.png') }}"
                                     alt="{{ $photo->user->name }}"
                                     class="w-8 h-8 rounded-full mr-2">
                                {{ $photo->user->name }}
                            </div>
                        </td>
                        <td class="p-4">{{ $photo->category }}</td>
                        <td class="p-4">
                            <div class="flex space-x-3 text-sm">
                                <span class="text-red-400"><i class="fas fa-heart"></i> {{ $photo->likes()->count() }}</span>
                                <span class="text-yellow-400"><i class="fas fa-bookmark"></i> {{ $photo->saves()->count() }}</span>
                                <span class="text-blue-400"><i class="fas fa-download"></i> {{ $photo->downloads }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            @if ($photo->is_featured)
                                <span class="px-2 py-1 bg-yellow-900 bg-opacity-50 text-yellow-400 rounded-full text-xs">Featured</span>
                            @else
                                <span class="px-2 py-1 bg-gray-900 bg-opacity-50 text-gray-400 rounded-full text-xs">Regular</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('gallery.show', $photo) }}" class="text-blue-400 hover:text-blue-300" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.photo.toggle-featured', $photo) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-400 hover:text-yellow-300" title="Toggle Featured">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                                <a href="{{ route('gallery.edit', $photo) }}" class="text-green-400 hover:text-green-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-dark-muted">No photos found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-dark-border">
        {{ $photos->links() }}
    </div>
</div>
@endsection
