<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="/login.css">
</head>
<body>

<div class="background"></div>

<div class="login-box">
    <h2>Register</h2>

    <!-- HIỂN THỊ LỖI -->
    @if ($errors->any())
        <div style="color: #ff6b6b; margin-bottom: 10px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="/register" method="POST">
        @csrf

        <div class="input-box">
            <input type="text" name="name" value="{{ old('name') }}" required>
            <label>Username</label>
            <span class="icon">👤</span>
        </div>

        <div class="input-box">
            <input type="email" name="email" value="{{ old('email') }}" required>
            <label>Email</label>
            <span class="icon">📧</span>
        </div>

        <div class="input-box">
            <input type="password" name="password" required>
            <label>Password</label>
            <span class="icon">🔒</span>
        </div>

        <div class="input-box">
            <input type="password" name="password_confirmation" required>
            <label>Confirm Password</label>
            <span class="icon">🔒</span>
        </div>

        <button type="submit" class="btn">Register</button>
    </form>

    <div class="register">
        Already have an account? <a href="/login">Login</a>
    </div>
</div>
  
</body>
</html>