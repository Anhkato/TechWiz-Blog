<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;  
use App\Http\Controllers\CommentController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PostController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('posts', PostController::class)->except(['index', 'show'])
    ->middleware(['auth', 'verified']);

Route::get('/blog', [PostController::class, 'index'])->name('posts.index'); 
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('posts.show'); 


Route::post('/blog/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
require __DIR__.'/auth.php';
