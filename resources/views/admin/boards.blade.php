// resources/views/admin/boards.blade.php
@extends('layouts.app')

@section('title', 'Manage Boards')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Manage Boards</h1>
    <p class="text-dark-muted">View and manage all user boards</p>
</div>

<div class="bg-dark-card rounded-lg border border-dark-border">
    <div class="p-6 border-b border-dark-border">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-bold">All Boards</h2>
            <div class="mt-4 sm:mt-0">
                <div class="relative">
                    <input type="text" placeholder="Search boards..." class="bg-dark-bg border border-dark-border rounded-lg py-2 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500 w-full sm:w-64">
                    <i class="fas fa-search absolute right-3 top-3 text-dark-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-dark-bg text-left">
                    <th class="p-4">Board</th>
                    <th class="p-4">Owner</th>
                    <th class="p-4">Photos</th>
                    <th class="p-4">Created</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($boards as $board)
                    <tr class="border-b border-dark-border hover:bg-dark-bg transition">
                        <td class="p-4">
                            <div class="flex items-center">
                                @if ($board->cover_image)
                                    <img src="{{ asset($board->cover_image) }}"
                                         alt="{{ $board->name }}"
                                         class="w-16 h-16 object-cover rounded mr-3">
                                @else
                                    <div class="w-16 h-16 bg-dark-bg rounded mr-3 flex items-center justify-center">
                                        <i class="fas fa-folder text-dark-muted text-xl"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium max-w-xs truncate">{{ $board->name }}</p>
                                    <p class="text-sm text-dark-muted">{{ Str::limit($board->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center">
                                <img src="{{ $board->user->avatar ?: asset('images/default-avatar.png') }}"
                                     alt="{{ $board->user->name }}"
                                     class="w-8 h-8 rounded-full mr-2">
                                {{ $board->user->name }}
                            </div>
                        </td>
                        <td class="p-4">{{ $board->photos->count() }}</td>
                        <td class="p-4">{{ $board->created_at->format('M j, Y') }}</td>
                        <td class="p-4">
                            @if ($board->is_private)
                                <span class="px-2 py-1 bg-red-900 bg-opacity-50 text-red-400 rounded-full text-xs">Private</span>
                            @else
                                <span class="px-2 py-1 bg-green-900 bg-opacity-50 text-green-400 rounded-full text-xs">Public</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('boards.show', $board) }}" class="text-blue-400 hover:text-blue-300" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('boards.edit', $board) }}" class="text-green-400 hover:text-green-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-dark-muted">No boards found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-dark-border">
        {{ $boards->links() }}
    </div>
</div>
@endsection
