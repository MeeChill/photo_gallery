@extends('layouts.app')
@section('title', 'Register - PinSpace')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <a href="{{ route('gallery.index') }}" class="flex items-center justify-center">
                <i class="fas fa-images text-4xl text-purple-500 mr-3"></i>
                <span class="text-3xl font-bold text-white">PinSpace</span>
            </a>
            <h2 class="mt-6 text-2xl font-bold text-white">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-gray-400">
                Bergabunglah dengan komunitas kami dan bagikan karya terbaik Anda
            </p>
        </div>

        <div class="bg-dark-card rounded-lg shadow-xl border border-dark-border p-8">
            @if (session('error'))
                <div class="mb-4 bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300">Nama Lengkap</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               autofocus
                               class="pl-10 block w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap">
                        @error('name')
                            <div class="mt-1 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-500"></i>
                        </div>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="pl-10 block w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="nama@email.com">
                        @error('email')
                            <div class="mt-1 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500"></i>
                        </div>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               class="pl-10 block w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <div class="mt-1 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password-confirm" class="block text-sm font-medium text-gray-300">Konfirmasi Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500"></i>
                        </div>
                        <input type="password"
                               id="password-confirm"
                               name="password_confirmation"
                               required
                               class="pl-10 block w-full bg-dark-bg border border-dark-border rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox"
                           id="terms"
                           name="terms"
                           required
                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-300">
                        Saya setuju dengan <a href="#" class="text-purple-400 hover:text-purple-300">Syarat & Ketentuan</a>
                    </label>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Buat Akun
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-dark-card text-gray-400">Atau daftar dengan</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-3 gap-3">
                    <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-700 rounded-md shadow-sm bg-dark-bg text-sm font-medium text-gray-300 hover:bg-gray-800 transition duration-200">
                        <i class="fab fa-google"></i>
                    </button>
                    <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-700 rounded-md shadow-sm bg-dark-bg text-sm font-medium text-gray-300 hover:bg-gray-800 transition duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </button>
                    <button class="w-full inline-flex justify-center py-2 px-4 border border-gray-700 rounded-md shadow-sm bg-dark-bg text-sm font-medium text-gray-300 hover:bg-gray-800 transition duration-200">
                        <i class="fab fa-github"></i>
                    </button>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-sm text-gray-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-purple-400 hover:text-purple-300 transition duration-200">
                Login sekarang
            </a>
        </p>
    </div>
</div>
@endsection
