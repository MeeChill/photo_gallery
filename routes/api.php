<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\SaveController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);

    // User routes
    Route::get('user/profile', [UserController::class, 'profile']);
    Route::put('user/profile', [UserController::class, 'updateProfile']);
    Route::get('user/photos', [UserController::class, 'photos']);
    Route::get('user/saved-photos', [UserController::class, 'savedPhotos']);
    Route::get('user/liked-photos', [UserController::class, 'likedPhotos']);

    // Photo routes
    Route::get('photos', [PhotoController::class, 'index']);
    Route::post('photos', [PhotoController::class, 'store']);
    Route::get('photos/{id}', [PhotoController::class, 'show']);
    Route::put('photos/{id}', [PhotoController::class, 'update']);
    Route::delete('photos/{id}', [PhotoController::class, 'destroy']);
    Route::get('photos/{id}/download', [PhotoController::class, 'download']);

    // Board routes
    Route::get('boards', [BoardController::class, 'index']);
    Route::post('boards', [BoardController::class, 'store']);
    Route::get('boards/{id}', [BoardController::class, 'show']);
    Route::put('boards/{id}', [BoardController::class, 'update']);
    Route::delete('boards/{id}', [BoardController::class, 'destroy']);
    Route::post('boards/{id}/add-photo', [BoardController::class, 'addPhoto']);
    Route::delete('boards/{id}/photos/{photoId}', [BoardController::class, 'removePhoto']);
    Route::get('boards/{id}/photos', [BoardController::class, 'photos']);

    // Like routes
    Route::post('photos/{photoId}/like', [LikeController::class, 'toggle']);
    Route::get('user/likes', [LikeController::class, 'userLikes']);

    // Save routes
    Route::post('photos/{photoId}/save', [SaveController::class, 'toggle']);
    Route::post('photos/{photoId}/save-to-board', [SaveController::class, 'saveToBoard']);
    Route::get('user/saves', [SaveController::class, 'userSaves']);
});
