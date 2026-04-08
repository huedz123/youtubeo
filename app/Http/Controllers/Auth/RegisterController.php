<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm() {
    return view('register'); // FIX bỏ dấu /
}

public function register(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $avatars = [
    'avatar1.png',
    'avatar2.png',
    'avatar3.png',
    'avatar4.png',
    'avatar5.png'
];

// Lấy file ngẫu nhiên
$defaultAvatar = $avatars[array_rand($avatars)] ?? 'avatar1.png';

// Lưu path vào DB (chỉ filename an toàn, không chứa ../)
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'avatar' => 'avatars/' . $defaultAvatar, // lưu path trong storage
]);

    auth()->login($user);
    return redirect('/home');
}
}