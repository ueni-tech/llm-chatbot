<?php

use App\Http\Controllers\OpenAIController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [OpenAIController::class, 'index'])->name('openai.index');
Route::post('/send-message', [OpenAIController::class, 'sendMessage'])->name('openai.send');
