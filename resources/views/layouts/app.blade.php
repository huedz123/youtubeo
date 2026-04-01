<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>YouTubeo</title>
  <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>

<!-- HEADER -->
<header class="header">
  <div class="left">
    <span class="menu" onclick="toggleMenu()">☰</span>
    <a href="/"  class="logo-box">
    <img src="logo_new.png" class="logo">
    <span class="name">YouTubeo</span>
</a>
  </div>

  <div class="search-bar">
   <input type="text" id="searchInput" placeholder="Tìm kiếm">
<button onclick="searchVideo()">🔍</button>
  </div>

  <div class="right">

      <a href="{{ route('studio') }}" class="upload-btn">Thêm</a>

    <span class="icon">🔔</span>

   @if(Auth::check())
<div class="avatar-wrapper">

    <!-- AVATAR -->
    <img src="{{ asset('avatars/' . Auth::user()->avatar) }}" 
         class="avatar-img"
         onclick="toggleAvatarMenu()">

    <!-- MENU -->
    <div id="avatarMenu" class="avatar-menu">
        <a href="/profile">Kênh của bạn</a>
<a href="/studio">YouTube Studio</a>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">Đăng xuất</button>
        </form>
    </div>

</div>
    
@else
        <button class="login-btn" onclick="goToLogin()">Đăng nhập</button>
    @endif

</div>
</header>

<!-- MAIN -->
<div class="container">

  <!-- SIDEBAR -->
  <aside class="sidebar collapsed" id="sidebar">
  <div class="item" >
    <span>🏠</span>
    <p>Trang chủ</p>
  </div>

  <div class="item">
    <span>🎬</span>
    <p>Shorts</p>
  </div>

  <div class="item">
    <span>📺</span>
    <p>Kênh đăng ký</p>
  </div>

  <div class="item">
    <span>👥</span>
    <p>Bạn</p>
  </div>
</aside>

  <!-- CONTENT -->
  <main class="content">
<div class="video-section">
    <!-- CATEGORY -->
    <div class="categories">
      <button class="active">Tất cả</button>
      <button>Âm nhạc</button>
      <button>Trò chơi</button>
      <button>Tin tức</button>
    </div>

    <!-- VIDEO GRID -->
      <div class="videos">
  @foreach($videos as $video)
    <a href="{{ route('video.show', $video->id) }}">
      <div class="video-card">
        @if($video->thumbnail)
          <img src="{{ asset('thumbnails/' . $video->thumbnail) }}" 
               alt="{{ $video->title }}" class="thumbnail-img">
        @endif
        <h4>{{ $video->title }}</h4>
        <p>{{ $video->views ?? 0 }} lượt xem</p>
      </div>
    </a>
  @endforeach

  @if($videos->isEmpty())
    <p>Chưa có video nào được tải lên.</p>
  @endif
</div>
</div>
  </main>
</div>

</body>
</html>