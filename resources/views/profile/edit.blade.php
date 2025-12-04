@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-dark-bg py-10">

    <div class="max-w-3xl mx-auto bg-dark-card border border-dark-border rounded-xl p-8 shadow-lg">

        {{-- BACK BUTTON --}}
        <a href="{{ route('profile.show', auth()->id()) }}"
           class="inline-flex items-center px-4 py-2 mb-6 bg-dark-card text-white border border-dark-border rounded-lg hover:bg-dark-border transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Profil
        </a>

        {{-- Title --}}
        <h1 class="text-2xl font-bold text-white mb-8">Pengaturan Profile</h1>

        {{-- AVATAR --}}
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-white mb-3">Avatar</h2>

            <div class="flex items-center space-x-4">
                <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name }}"
                     class="w-24 h-24 rounded-full border-4 border-purple-600 object-cover">

                <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar"
                           class="text-sm text-gray-300 bg-dark-bg border border-dark-border rounded p-2">

                    <button class="mt-2 px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Update Avatar
                    </button>
                </form>
            </div>

            @error('avatar')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- EDIT PROFILE --}}
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-white mb-3">Edit Data User</h2>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-dark-muted">Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}"
                           class="w-full bg-dark-bg border border-dark-border text-white rounded p-2">
                </div>

                <div>
                    <label class="text-dark-muted">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}"
                           class="w-full bg-dark-bg border border-dark-border text-white rounded p-2">
                </div>

                <button class="px-5 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- CHANGE PASSWORD --}}
        <div class="mb-10">
            <h2 class="text-lg font-semibold text-white mb-3">Ubah Password</h2>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-dark-muted">Password Lama</label>
                    <input type="password" name="current_password"
                           class="w-full bg-dark-bg border border-dark-border text-white rounded p-2">
                </div>

                <div>
                    <label class="text-dark-muted">Password Baru</label>
                    <input type="password" name="password"
                           class="w-full bg-dark-bg border border-dark-border text-white rounded p-2">
                </div>

                <div>
                    <label class="text-dark-muted">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-dark-bg border border-dark-border text-white rounded p-2">
                </div>

                <button class="px-5 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    Update Password
                </button>
            </form>

            @error('current_password')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

    </div>
</div>
@endsection
