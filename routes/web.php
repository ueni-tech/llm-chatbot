<?php

use App\Http\Controllers\OpenAIController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
require __DIR__.'/auth.php';

Route::get('/', [OpenAIController::class, 'index'])->middleware(['auth', 'verified'])->name('openai.index');
Route::post('/send-message', [OpenAIController::class, 'sendMessage'])->middleware(['auth', 'verified'])->name('openai.send');
