<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang cá nhân</title>
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  <script src="{{ asset('script.js') }}"></script>
</head>
<body>

<!-- HEADER -->
<header class="header">
  <div class="left">
    <span class="menu">☰</span>
    <a href="/" class="logo-box">
      <img src="{{ asset('logo.jpg') }}" class="logo">
      <span class="name">YouTubeo</span>
    </a>
  </div>

  <div class="search-bar">
    <input type="text" placeholder="Tìm kiếm">
    <button>🔍</button>
  </div>

  <div class="right">
    <a href="{{ route('studio') }}" class="upload-btn">Thêm</a>
    <span class="icon">🔔</span>

    @if(Auth::check())
      <div class="avatar-wrapper">

        <!-- AVATAR -->
        <img src="{{ Storage::url(Auth::user()->avatar ?? 'avatars/default.png') }}" class="avatar-img"
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
        <a href="/login">Đăng nhập</a>
    @endif
  </div>
</header>

<div class="container">

  <!-- SIDEBAR -->
  <aside class="sidebar collapsed">
    <a href="/" class="item">
  <span>🏠</span>
  <p>Trang chủ</p>
</a>
    <div class="item"><span>🎬</span><p>Shorts</p></div>
    <div class="item"><span>📺</span><p>Kênh đăng kí</p></div>
  </aside>

  <!-- CONTENT -->
  <main class="content">

    <!-- CHANNEL HEADER -->
    <div class="channel-header">

      <div class="channel-avatar">
     <img src="{{ Storage::url(Auth::user()->avatar ?? 'avatars/default.png') }}" class="avatar-img">
      </div>

      <div class="channel-info">
        <h1>{{ Auth::user()->name }}</h1>

        <p class="username">
          {{ Auth::user()->email }}
        </p>

        <p class="meta">
          1 người đăng ký · {{ $videos->count() }} video
        </p>

        <div class="channel-actions">
          <button class="btn-gray">Tùy chỉnh kênh</button>
          <button class="btn-gray" onclick="window.location.href='{{ route('studio') }}'">Quản lý video</button>
        </div>
      </div>

    </div>

    <!-- TAB -->
    <div class="channel-tabs">
      <span class="active">Shorts</span>
      <span>Bài đăng</span>
    </div>

    <!-- VIDEO GRID -->
    <div class="videos">
  @foreach($videos as $video)
    <a href="{{ route('video.show', $video->id) }}">
      <div class="video-card">
        @if($video->thumbnail)
         <img src="{{ asset('storage/' . $video->thumbnail) }}" 
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

<script>
function toggleAvatarMenu() {
    const menu = document.getElementById("avatarMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

window.onclick = function(event) {
    if (!event.target.matches('.avatar-img')) {
        const menu = document.getElementById("avatarMenu");
        if (menu) menu.style.display = "none";
    }
}
</script>
</body>
</html>