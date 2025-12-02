<!-- resources/views/gallery/index.blade.php -->
@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<!-- Category Filter -->
<div class="mb-6 overflow-x-auto">
    <div class="flex space-x-2 pb-2">
        <a href="{{ route('gallery.index', ['category' => 'all']) }}"
           class="px-4 py-2 rounded-full {{ request('category') == 'all' || !request()->has('category') ? 'bg-purple-600' : 'bg-dark-card hover:bg-dark-bg' }} transition whitespace-nowrap">
            All
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('gallery.index', ['category' => $category]) }}"
               class="px-4 py-2 rounded-full {{ request('category') == $category ? 'bg-purple-600' : 'bg-dark-card hover:bg-dark-bg' }} transition whitespace-nowrap">
                {{ ucfirst($category) }}
            </a>
        @endforeach
    </div>
</div>

<!-- Photo Grid -->
<div id="photo-container" class="masonry-grid">
    @include('gallery.partials.photos', ['photos' => $photos])
</div>

<!-- Loading Indicator -->
<div id="loading" class="text-center py-8 hidden">
    <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-purple-500"></div>
</div>

<!-- No Photos Message -->
@if ($photos->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-images text-6xl text-dark-muted mb-4"></i>
        <h3 class="text-xl font-semibold mb-2">No photos found</h3>
        <p class="text-dark-muted mb-4">Be the first to upload a photo!</p>
        @if (auth()->check())
            <a href="{{ route('gallery.create') }}" class="px-6 py-3 bg-purple-600 rounded-lg hover:bg-purple-700 transition inline-block">
                <i class="fas fa-plus mr-2"></i>Upload Photo
            </a>
        @else
            <a href="{{ route('register') }}" class="px-6 py-3 bg-purple-600 rounded-lg hover:bg-purple-700 transition inline-block">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </a>
        @endif
    </div>
@endif
@endsection

@push('scripts')
<script>
    let page = {{ $photos->currentPage() }};
    let loading = false;
    let hasMorePages = {{ $photos->hasMorePages() ? 'true' : 'false' }};

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
            loadMorePhotos();
        }
    });

    function loadMorePhotos() {
        if (loading || !hasMorePages) return;

        loading = true;
        $('#loading').show();

        let category = '{{ request('category') }}';
        let search = '{{ request('search') }}';
        let url = '{{ route('gallery.index') }}?page=' + (page + 1);

        if (category && category !== 'all') {
            url += '&category=' + category;
        }

        if(search) {
            url += '&search=' + encodeURIComponent(search); 

        }

        $.get(url, function(data) {
            if (data.trim() === '') {
                hasMorePages = false;
            } else {
                $('#photo-container').append(data);
                page++;
            }
            loading = false;
            $('#loading').hide();
        });
    }
</script>
@endpush
