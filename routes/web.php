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

Route::get('/{conversationId?}', [ChatbotController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
Route::get('/chat/{conversationId?}', [ChatbotController::class, 'index'])->middleware(['auth', 'verified'])->name('chat.index');
Route::post('/chat/{conversationId?}', [ChatbotController::class, 'chat'])->middleware(['auth', 'verified'])->name('chat');

