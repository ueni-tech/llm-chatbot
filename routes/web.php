<?php

use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
require __DIR__.'/auth.php';

Route::get('/', [ChatbotController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
Route::post('/chat', [ChatbotController::class, 'chat'])->middleware(['auth', 'verified'])->name('chat');
