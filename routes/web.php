<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\StudioController; // ✅ thêm dòng này
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// =======================
// TRANG CHỦ
// =======================

// Trang chủ
Route::get('/', [VideoController::class, 'index'])->name('home');
Route::get('/home', [VideoController::class, 'index'])->middleware('auth');


// =======================
// VIDEO
// =======================

// Video chi tiết + tương tác
Route::get('/video/{id}', [VideoController::class, 'show'])->name('video.show');

Route::middleware(['auth','throttle:10,1'])->group(function () {
   // web.php
Route::post('/video/{id}/like', [VideoController::class, 'like'])->name('video.like');
Route::post('/video/{id}/dislike', [VideoController::class, 'dislike'])->name('video.dislike');
    Route::post('/video/{id}/comment', [VideoController::class, 'postComment'])->name('video.comment');
});

// =======================
// STUDIO (gộp lại 1 chỗ)
// =======================

// Studio (upload, quản lý)
Route::prefix('studio')->middleware('auth')->group(function() {
    Route::get('/', [VideoController::class, 'showForm'])->name('studio');
    Route::post('upload', [VideoController::class, 'upload'])->name('studio.upload');
    Route::put('video/{id}', [StudioController::class, 'update'])->name('studio.video.update');
    Route::delete('video/{id}', [StudioController::class, 'destroy'])->name('studio.video.destroy');
});
// =======================
// Profile
Route::get('/profile', function () {
    $videos = \App\Models\Video::where('user_id', auth()->id())->get();
    return view('profile', compact('videos'));
})->middleware('auth')->name('profile');


// =======================
// AUTH
// =======================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/video/{id}/like', [VideoController::class, 'like'])
    ->middleware('auth', 'throttle:15,1');

Route::post('/video/{id}/dislike', [VideoController::class, 'dislike'])
    ->middleware('auth', 'throttle:15,1');
