@extends('layouts.app')
@section('title', 'My Profile - PinSpace')

@section('content')
<div class="min-h-screen bg-dark-bg py-10">

    <div class="max-w-5xl mx-auto bg-dark-card border border-dark-border rounded-xl shadow-lg p-8">

        {{-- Header Profile --}}
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
            <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=6b21a8&color=fff&size=128"
                 class="w-32 h-32 rounded-full border-4 border-purple-600 shadow-lg">

            <div>
                <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                <p class="text-dark-muted">{{ $user->email }}</p>

                <a href="#"
                   class="mt-3 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-10">

            <div class="bg-dark-bg p-6 rounded-lg border border-dark-border text-center">
                <i class="fas fa-images text-purple-500 text-3xl mb-2"></i>
                <p class="text-dark-muted text-sm">Total Foto</p>
                <p class="text-white text-2xl font-bold">{{ $user->photos->count() }}</p>
            </div>

            <div class="bg-dark-bg p-6 rounded-lg border border-dark-border text-center">
                <i class="fas fa-heart text-red-500 text-3xl mb-2"></i>
                <p class="text-dark-muted text-sm">Total Likes</p>
                <p class="text-white text-2xl font-bold">
                    {{ $user->photos->sum(fn($p) => $p->likes()->count()) }}
                </p>
            </div>

            <div class="bg-dark-bg p-6 rounded-lg border border-dark-border text-center">
                <i class="fas fa-download text-green-500 text-3xl mb-2"></i>
                <p class="text-dark-muted text-sm">Downloads</p>
                <p class="text-white text-2xl font-bold">{{ $user->photos->sum('downloads') }}</p>
            </div>

            <div class="bg-dark-bg p-6 rounded-lg border border-dark-border text-center">
                <i class="fas fa-folder text-blue-500 text-3xl mb-2"></i>
                <p class="text-dark-muted text-sm">Boards</p>
                <p class="text-white text-2xl font-bold">{{ $user->boards->count() }}</p>
            </div>

        </div>

        {{-- All Photos --}}
        <div class="mt-12">
            <h2 class="text-xl font-bold text-white mb-4">Semua Foto</h2>

            @if ($photos->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                    @foreach ($photos as $photo)
                        <div class="bg-dark-bg rounded-lg overflow-hidden border border-dark-border hover:shadow-lg transition group">

                            <img src="{{ asset($photo->image_path) }}"
                                 alt="{{ $photo->title }}"
                                 class="h-40 w-full object-cover group-hover:opacity-80 transition">

                            <div class="p-3">
                                <h3 class="text-white font-semibold truncate">{{ $photo->title }}</h3>
                                <p class="text-dark-muted text-sm">{{ $photo->created_at->format('d M Y') }}</p>

                                <div class="flex items-center justify-between text-sm mt-2 text-dark-muted">
                                    <span class="flex items-center">
                                        <i class="fas fa-heart text-red-500 mr-1"></i>
                                        {{ $photo->likes()->count() }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-download text-green-500 mr-1"></i>
                                        {{ $photo->downloads }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-dark-bg border border-dark-border rounded-lg p-10 text-center">
                    <i class="fas fa-images text-5xl text-dark-muted mb-3"></i>
                    <h3 class="text-white text-xl mb-2">Belum ada foto</h3>
                    <p class="text-dark-muted mb-5">Ayo upload foto pertamamu!</p>
                    <a href="{{ route('gallery.create') }}"
                       class="px-5 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-plus mr-2"></i> Upload Foto
                    </a>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
