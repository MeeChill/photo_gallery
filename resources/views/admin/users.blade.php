// resources/views/admin/users.blade.php
@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold mb-2">Manage Users</h1>
    <p class="text-dark-muted">View and manage all registered users</p>
</div>

<div class="bg-dark-card rounded-lg border border-dark-border">
    <div class="p-6 border-b border-dark-border">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-bold">All Users</h2>
            <div class="mt-4 sm:mt-0">
                <div class="relative">
                    <input type="text" placeholder="Search users..." class="bg-dark-bg border border-dark-border rounded-lg py-2 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500 w-full sm:w-64">
                    <i class="fas fa-search absolute right-3 top-3 text-dark-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-dark-bg text-left">
                    <th class="p-4">User</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Joined</th>
                    <th class="p-4">Photos</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-dark-border hover:bg-dark-bg transition">
                        <td class="p-4">
                            <div class="flex items-center">
                                <img src="{{ $user->avatar ?: asset('images/default-avatar.png') }}"
                                     alt="{{ $user->name }}"
                                     class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <p class="font-medium">{{ $user->name }}</p>
                                    @if ($user->is_admin)
                                        <span class="text-xs px-2 py-1 bg-purple-900 bg-opacity-50 text-purple-400 rounded-full">Admin</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="p-4">{{ $user->email }}</td>
                        <td class="p-4">{{ $user->created_at->format('M j, Y') }}</td>
                        <td class="p-4">{{ $user->photos()->count() }}</td>
                        <td class="p-4">
                            @if ($user->is_active)
                                <span class="px-2 py-1 bg-green-900 bg-opacity-50 text-green-400 rounded-full text-xs">Active</span>
                            @else
                                <span class="px-2 py-1 bg-red-900 bg-opacity-50 text-red-400 rounded-full text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('profile.show', $user) }}" class="text-blue-400 hover:text-blue-300">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.user.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-400 hover:text-yellow-300" title="Toggle Status">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-dark-muted">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-dark-border">
        {{ $users->links() }}
    </div>
</div>
@endsection
