<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="/login.css">
</head>
<body>

<div class="background"></div>

<div class="login-box">
    <h2>Login</h2>

    <form action="/login" method="POST">
        @csrf
        <div class="input-box">
            <input type="email" id="username" name="email" required>
            <label>Email</label>
            <span class="icon">👤</span>
        </div>

        <div class="input-box">
            <input type="password" id="password" name="password" required>
            <label>Password</label>
            <span class="icon">🔒</span>
        </div>

        <div class="options">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="#">Forgot Password?</a>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>

    <div class="register">
        Don't have an account? <a href="/register">Register</a>
    </div>
</div>
  
</body>
</html>