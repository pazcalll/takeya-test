<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('posts', PostController::class)->only(['show', 'index']);

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)->except(['show', 'index']);
    Route::resource('profile', ProfileController::class)->only(['edit', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
