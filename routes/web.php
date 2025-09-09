<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\SaveController;

// Auth routes
Auth::routes();

// Home route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Gallery routes
Route::get('/', [PhotoController::class, 'index'])->name('gallery.index');
Route::get('/gallery', [PhotoController::class, 'index'])->name('gallery.index');
Route::get('/gallery/create', [PhotoController::class, 'create'])->name('gallery.create')->middleware('auth');
Route::post('/gallery', [PhotoController::class, 'store'])->name('gallery.store')->middleware('auth');
Route::get('/gallery/{photo}/edit', [PhotoController::class, 'edit'])->name('gallery.edit')->middleware('auth');
Route::put('/gallery/{photo}', [PhotoController::class, 'update'])->name('gallery.update')->middleware('auth');
Route::delete('/gallery/{photo}', [PhotoController::class, 'destroy'])->name('gallery.destroy')->middleware('auth');
Route::get('/gallery/{photo}', [PhotoController::class, 'show'])->name('gallery.show');
Route::get('/photos/{photo}/download', [PhotoController::class, 'download'])->name('photos.download');

// Like and Save routes - Perbaikan URL
Route::post('/gallery/{photo}/like', [PhotoController::class, 'like'])->name('gallery.like')->middleware('auth');
Route::post('/gallery/{photo}/save', [PhotoController::class, 'save'])->name('gallery.save')->middleware('auth');
Route::get('/saved', [App\Http\Controllers\SaveController::class, 'index'])->name('saved.index')->middleware('auth');

// Board routes
Route::get('/boards', [BoardController::class, 'index'])->name('boards.index')->middleware('auth');
Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create')->middleware('auth');
Route::post('/boards', [BoardController::class, 'store'])->name('boards.store')->middleware('auth');
Route::get('/boards/{board}', [BoardController::class, 'show'])->name('boards.show');
Route::get('/boards/{board}/edit', [BoardController::class, 'edit'])->name('boards.edit')->middleware('auth');
Route::put('/boards/{board}', [BoardController::class, 'update'])->name('boards.update')->middleware('auth');
Route::delete('/boards/{board}', [BoardController::class, 'destroy'])->name('boards.destroy')->middleware('auth');
Route::post('/boards/{board}/add-photo', [BoardController::class, 'addPhoto'])->name('boards.addPhoto')->middleware('auth');
Route::delete('/boards/{board}/photos/{photo}', [BoardController::class, 'removePhoto'])->name('boards.removePhoto')->middleware('auth');

// Save to board route
Route::post('/photos/{photo}/save-to-board', [SaveController::class, 'saveToBoard'])->name('photos.saveToBoard')->middleware('auth');

// Profile routes
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
