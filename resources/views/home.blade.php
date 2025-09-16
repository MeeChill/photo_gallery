@extends('layouts.app')
@section('title', 'Dashboard - DarkGallery')

@section('content')
<div class="min-h-screen bg-dark-bg">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-6 md:mb-0">
                    <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                    <p class="mt-2 text-purple-200">Selamat datang kembali, {{ Auth::user()->name }}!</p>
                </div>
                <a href="{{ route('gallery.create') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Upload Foto Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Photos Card -->
            <div class="bg-dark-card rounded-lg shadow-lg p-6 border border-dark-border hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-images text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-dark-muted">Total Foto</p>
                        <p class="text-2xl font-semibold text-white">{{ Auth::user()->photos->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Likes Card -->
            <div class="bg-dark-card rounded-lg shadow-lg p-6 border border-dark-border hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-dark-muted">Total Likes</p>
                        <p class="text-2xl font-semibold text-white">
                            {{ Auth::user()->photos->sum(function($photo) { return $photo->likes()->count(); }) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Downloads Card -->
            <div class="bg-dark-card rounded-lg shadow-lg p-6 border border-dark-border hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-download text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-dark-muted">Total Downloads</p>
                        <p class="text-2xl font-semibold text-white">
                            {{ Auth::user()->photos->sum('downloads') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Boards Card -->
            <div class="bg-dark-card rounded-lg shadow-lg p-6 border border-dark-border hover:shadow-xl transition duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-dark-muted">Total Boards</p>
                        <p class="text-2xl font-semibold text-white">{{ Auth::user()->boards->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white">Foto Terbaru</h2>
            <div class="flex space-x-2">
                <button onclick="setView('grid')" id="gridViewBtn" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                    <i class="fas fa-th"></i>
                </button>
                <button onclick="setView('list')" id="listViewBtn" class="px-4 py-2 bg-dark-bg text-gray-300 rounded-lg hover:bg-dark-border transition duration-200">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        @if (Auth::user()->photos->count() > 0)
            <!-- Grid View -->
            <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($photos as $photo)
                    <div class="bg-dark-card rounded-lg overflow-hidden shadow-lg border border-dark-border hover:shadow-xl transition-all duration-300 group">
                        <div class="relative">
                            <img src="{{ asset($photo->image_path) }}"
                                 alt="{{ $photo->title }}"
                                 class="w-full h-48 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex space-x-2">
                                    <a href="{{ route('gallery.show', $photo->id) }}"
                                       class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center hover:bg-opacity-30 transition">
                                        <i class="fas fa-eye text-white"></i>
                                    </a>
                                    <a href="{{ route('gallery.edit', $photo->id) }}"
                                       class="w-10 h-10 rounded-full bg-blue-600 bg-opacity-80 flex items-center justify-center hover:bg-opacity-100 transition">
                                        <i class="fas fa-edit text-white"></i>
                                    </a>
                                    <form action="{{ route('gallery.destroy', $photo->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 rounded-full bg-red-600 bg-opacity-80 flex items-center justify-center hover:bg-opacity-100 transition"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">
                                            <i class="fas fa-trash text-white"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-white mb-1 truncate">{{ $photo->title }}</h3>
                            <div class="flex items-center justify-between text-sm text-dark-muted">
                                <span>{{ $photo->created_at->format('d M Y') }}</span>
                                <div class="flex items-center space-x-3">
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
                    </div>
                @endforeach
            </div>

            <!-- List View -->
            <div id="listView" class="hidden bg-dark-card rounded-lg shadow-lg border border-dark-border overflow-hidden">
                <div class="divide-y divide-dark-border">
                    @foreach (Auth::user()->photos() as $photo)
                        <div class="p-4 hover:bg-dark-bg transition duration-200">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset($photo->image_path) }}"
                                     alt="{{ $photo->title }}"
                                     class="w-20 h-20 rounded-lg object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-white">{{ $photo->title }}</h3>
                                    <p class="text-sm text-dark-muted">{{ $photo->description ? Str::limit($photo->description, 100) : 'Tidak ada deskripsi' }}</p>
                                    <div class="flex items-center space-x-4 mt-2 text-sm text-dark-muted">
                                        <span>{{ $photo->created_at->format('d M Y H:i') }}</span>
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
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('gallery.show', $photo->id) }}"
                                       class="px-3 py-1 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                    <a href="{{ route('gallery.edit', $photo->id) }}"
                                       class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('gallery.destroy', $photo->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- View All Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('profile.show', Auth::user()) }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                    Lihat Semua Foto
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        @else
            <div class="bg-dark-card rounded-lg shadow-lg border border-dark-border p-12 text-center">
                <i class="fas fa-images text-6xl text-dark-muted mb-4"></i>
                <h3 class="text-xl font-semibold text-white mb-2">Belum ada foto</h3>
                <p class="text-dark-muted mb-6">Mulai unggah foto pertama Anda untuk membangun galeri yang menakjubkan.</p>
                <a href="{{ route('gallery.create') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Upload Foto Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function setView(view) {
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');

        if (view === 'grid') {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            gridBtn.classList.add('bg-purple-600', 'text-white');
            gridBtn.classList.remove('bg-dark-bg', 'text-gray-300');
            listBtn.classList.remove('bg-purple-600', 'text-white');
            listBtn.classList.add('bg-dark-bg', 'text-gray-300');
        } else {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            listBtn.classList.add('bg-purple-600', 'text-white');
            listBtn.classList.remove('bg-dark-bg', 'text-gray-300');
            gridBtn.classList.remove('bg-purple-600', 'text-white');
            gridBtn.classList.add('bg-dark-bg', 'text-gray-300');
        }
    }
</script>
@endpush
