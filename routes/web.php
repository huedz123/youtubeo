<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\StudioController; // ✅ thêm dòng này
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// =======================
// TRANG CHỦ
// =======================

Route::get('/', [VideoController::class, 'index'])->name('home');
Route::get('/home', [VideoController::class, 'index'])->middleware('auth');

// =======================
// VIDEO
// =======================

Route::get('/video/{id}', [VideoController::class, 'show'])->name('video.show');
Route::middleware('auth')->group(function () {
Route::post('/video/{id}/like', [VideoController::class, 'like'])->name('video.like');
Route::post('/video/{id}/dislike', [VideoController::class, 'dislike'])->name('video.dislike');
Route::post('/video/{id}/comment', [VideoController::class, 'postComment'])->name('video.comment');
});
// =======================
// STUDIO (gộp lại 1 chỗ)
// =======================

Route::prefix('studio')->middleware('auth')->group(function() {

    // Trang studio
    Route::get('/', [VideoController::class, 'showForm'])->name('studio');

    // ✅ Upload dùng VideoController
    Route::post('upload', [VideoController::class, 'upload'])->name('studio.upload');

    // Sửa video
    Route::put('video/{id}', [StudioController::class, 'update'])->name('studio.video.update');

    // Xóa video
    Route::delete('video/{id}', [StudioController::class, 'destroy'])->name('video.destroy');
});

// =======================
// PROFILE
// =======================

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
