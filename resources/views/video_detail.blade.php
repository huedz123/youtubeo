<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>{{ $video->title }}</title>
  <link rel="stylesheet" href="{{ asset('style.css') }}">
<script src="{{ asset('script.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

<header class="header">
  <div class="left">
    <span class="menu" onclick="toggleMenu()">☰</span>
    <a href="/"  class="logo-box">
    <img src="{{ asset('logo_new.png') }}" class="logo">
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
        <button class="login-btn" onclick="goToLogin()">Đăng nhập</button>
    @endif

</div>
</header>

<div class="container">

  <aside class="sidebar collapsed">
  <a href="/" class="item">
  <span>🏠</span>
  <p>Trang chủ</p>
</a>
    <div class="item"><span>🎬</span><p>Shorts</p></div>
    <div class="item"><span>📺</span><p>Kênh đăng ký</p></div>
   <a href="/profile" class="item">
    <span>👥</span>
    <p>Bạn</p>
</a>
  </aside>

  <main class="content">

    <div class="watch-container">

      <!-- LEFT -->
      <div class="main-video">
        <video controls width="100%" autoplay>
    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
</video>

        <h2>{{ $video->title }}</h2>
        <p>{{ $video->views ?? 0 }} lượt xem</p>
<div class="channel-box">

  <div class="channel-info">
  <img src="{{ Storage::url(Auth::user()->avatar ?? 'avatars/default.png') }}" class="avatar-img">

    <div>
      <h4>{{ $video->user->name ?? 'Unknown' }}</h4>
      <p>{{ $video->user->videos->count() ?? 0 }} video</p>
    </div>
  </div>

  <button class="subscribe-btn">
    Đăng ký
  </button>

</div>
        <!-- ✅ FIX: actions nằm trong main-video -->
      <div class="video-actions">
    <button id="likeBtn" onclick="likeVideo({{ $video->id }})">
        👍 <span id="like-count">{{ $video->likes ?? 0 }}</span>
    </button>

    <button id="dislikeBtn" onclick="dislikeVideo({{ $video->id }})">
        👎 <span id="dislike-count">{{ $video->dislikes ?? 0 }}</span>
    </button>

    <button onclick="shareVideo({{ $video->id }})">
        🔗 Share
    </button>
</div>

        <!-- Description -->
        <div class="video-description">
          <p>{{ $video->description }}</p>
        </div>

        <!-- Comments -->
        <div class="comments-section">
          <h3>Bình luận</h3>

          @auth
<form id="comment-form" onsubmit="postComment(event, {{ $video->id }})">
    <textarea name="comment" placeholder="Viết bình luận..." required></textarea>
    <button type="submit">Đăng</button>
</form>
@else
<p>👉 Đăng nhập để bình luận</p>
@endif

          <div class="comments-list">
            @if($video->comments && $video->comments->count())
              @foreach($video->comments as $comment)
                <div class="comment">
                  <strong>{{ optional($comment->user)->name ?? 'Guest' }}</strong>
                  <p>{{ $comment->content }}</p>
                  <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
              @endforeach
            @else
              <p>Chưa có bình luận nào.</p>
            @endif
          </div>
        </div>

      </div>

      <!-- RIGHT -->
      <div class="suggested">
    @foreach($videos as $v)
        @if($v->id != $video->id)
        <a href="{{ route('video.show', $v->id) }}">
            <div class="suggest-card">
                <!-- Thumbnail hiển thị đúng kích cỡ -->
                <img src="{{ asset('storage/' . ($v->thumbnail ?? 'thumbnails/default.png')) }}" 
                     alt="{{ $v->title }}" 
                     class="thumbnail-img"
                     style="width: 160px; height: 90px; object-fit: cover;"> <!-- giữ tỉ lệ 16:9 -->

                <div class="suggest-info">
                    <h4>{{ $v->title }}</h4>
                    <p>{{ $v->views ?? 0 }} lượt xem</p>
                </div>
            </div>
        </a>
        @endif
    @endforeach
</div>

    </div>

  </main>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    updateButtons("{{ $userVote }}");
});
</script>
</body>
</html>