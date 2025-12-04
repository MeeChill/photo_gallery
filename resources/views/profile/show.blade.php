@extends('layouts.app')
@section('title', $user->name . ' - Profile')

@section('content')
<div class="min-h-screen bg-dark-bg py-10">

    <div class="max-w-4xl mx-auto text-center">

        {{-- Avatar --}}
        <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name }}"
             class="w-32 h-32 rounded-full mx-auto border-4 border-purple-600 object-cover">

        {{-- Name --}}
        <h1 class="text-3xl font-bold text-white mt-4">{{ $user->name }}</h1>

        {{-- Email --}}
        <p class="text-dark-muted text-center">{{ '@' . $user->email }}</p>

        {{-- Buttons --}}
        <div class="flex justify-center space-x-3 mt-5">
            <button class="px-4 py-2 rounded-lg bg-dark-card border border-dark-border text-white hover:bg-dark-border">
                Bagikan
            </button>

            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}"
                   class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700">
                    Edit profil
                </a>
            @endif
        </div>

        {{-- Tabs --}}
        <div class="flex justify-center space-x-10 mt-10 border-b border-dark-border pb-2">
            <button onclick="showMade()" id="madeBtn" class="text-white font-semibold border-b-2 border-purple-600 pb-1">
                Dibuat
            </button>
            <button onclick="showSaved()" id="savedBtn" class="text-dark-muted font-semibold hover:text-white">
                Disimpan
            </button>
        </div>

    </div>

    {{-- FOTO DIBUAT --}}
    <div id="madeTab" class="max-w-6xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-10 px-4">
        @forelse ($photos as $photo)
            <a href="{{ route('gallery.show', $photo->id) }}">
                <img src="{{ asset($photo->image_path) }}"
                     class="rounded-xl shadow-lg hover:opacity-80 transition h-60 w-full object-cover">
            </a>
        @empty
            <p class="text-dark-muted col-span-4 text-center">Belum ada foto.</p>
        @endforelse
    </div>

    {{-- FOTO DISIMPAN --}}
    <div id="savedTab" class="hidden max-w-6xl mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-10 px-4">
        @forelse ($saved as $photo)
            <a href="{{ route('gallery.show', $photo->id) }}">
                <img src="{{ asset($photo->image_path) }}"
                     class="rounded-xl shadow-lg hover:opacity-80 transition h-60 w-full object-cover">
            </a>
        @empty
            <p class="text-dark-muted col-span-4 text-center">Belum ada foto disimpan.</p>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script>
function showMade() {
    document.getElementById('madeTab').classList.remove('hidden');
    document.getElementById('savedTab').classList.add('hidden');

    document.getElementById('madeBtn').classList.add('text-white', 'border-purple-600');
    document.getElementById('savedBtn').classList.remove('text-white');
    document.getElementById('savedBtn').classList.add('text-dark-muted');
}

function showSaved() {
    document.getElementById('madeTab').classList.add('hidden');
    document.getElementById('savedTab').classList.remove('hidden');

    document.getElementById('savedBtn').classList.add('text-white', 'border-purple-600');
    document.getElementById('madeBtn').classList.remove('text-white');
    document.getElementById('madeBtn').classList.add('text-dark-muted');
}
</script>
@endpush
