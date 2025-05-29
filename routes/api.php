<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\UserResource as ApiUserResource;


Route::get('/posts', [PostController::class, 'index'])->name('api.posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('api.posts.show');
Route::get('/posts/{id}/comments', [PostController::class, 'comments'])->name('api.posts.comments');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return new ApiUserResource($request->user());
    });
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])->name('api.comments.store');
});