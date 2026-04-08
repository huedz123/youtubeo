<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>YouTubeo Studio</title>
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  <style>
    body { margin:0; font-family:Arial; background:#f9f9f9; }
    .header { display:flex; justify-content:space-between; padding:10px 20px; background:#fff; border-bottom:1px solid #ddd; }
    .logo { width:40px; } .avatar-img { width:35px; border-radius:50%; cursor:pointer; }
    .container { display:flex; }
    .sidebar { width:240px; background:#fff; height:100vh; border-right:1px solid #ddd; position:fixed; left:0; top:60px; }
    .channel-header { display:flex; flex-direction:column; align-items:center; text-align:center; padding:20px; border-bottom:1px solid #eee; background:#fff; }
    .channel-avatar { width:90px; height:90px; border-radius:50%; object-fit:cover; margin-bottom:5px; }
    .channel-header h3 { margin:5px 0 0; font-size:16px; }
    .channel-header p { margin:0; color:#666; font-size:13px; }
    .item { display:block; padding:12px 20px; text-decoration:none; color:black; }
    .item:hover { background:#f1f1f1; } .item.active { background:#eee; }
    .content { margin-left:240px; flex:1; padding:20px; width:calc(100%-240px); }
    .tabs { display:flex; gap:20px; border-bottom:1px solid #ddd; padding-bottom:10px; }
    .tabs .active { border-bottom:2px solid black; font-weight:bold; }
    .filter { margin:10px 0; cursor:pointer; }
    table { width:100%; border-collapse:collapse; background:#fff; }
    th, td { padding:10px; border-bottom:1px solid #eee; }
    .video-cell { display:flex; gap:10px; align-items:center; }
    .thumb { width:120px; height:70px; object-fit:cover; border-radius:6px; }
    .title { font-weight:bold; }
    .upload-modal { display:none; position:fixed; z-index:999; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); }
    .upload-box { background:#fff; width:500px; margin:80px auto; padding:30px; border-radius:10px; text-align:center; position:relative; }
    .close { position:absolute; right:15px; top:10px; cursor:pointer; font-size:20px; }
    .upload-box input, .upload-box textarea { width:90%; padding:8px; margin-top:10px; }
    .upload-box button { margin-top:10px; padding:10px 20px; background:black; color:white; border:none; border-radius:20px; cursor:pointer; }
    .note { margin-top:15px; font-size:13px; color:gray; }
  </style>
</head>
<body>

<header class="header">
  <div class="left">
    <a href="/" class="logo-box">
      <img src="{{ asset('logo_new.png') }}" class="logo">
      <span class="name">YouTubeo</span>
    </a>
  </div>
  <div class="right">
    <button class="upload-btn" onclick="openUploadModal()">Thêm</button>
    <span class="icon">🔔</span>
    @if(Auth::check())
    <div class="avatar-wrapper">
    <!-- avatar img chỉ còn class, không onclick -->
<img src="{{ Storage::url(Auth::user()->avatar ?? 'avatars/default.png') }}" class="avatar-img">
      <div id="avatarMenu" class="avatar-menu" style="display:none;">
          <a href="/profile">Kênh của bạn</a>
          <a href="/studio">YouTube Studio</a>
          <form action="/logout" method="POST">@csrf<button type="submit">Đăng xuất</button></form>
      </div>
    </div>
    @else
      <a href="/login">Đăng nhập</a>
    @endif
  </div>
</header>

<div class="container">
<aside class="sidebar">
  <div class="channel-header">
    <img src="{{ Storage::url(Auth::user()->avatar ?? 'avatars/default.png') }}" class="avatar-img">
    <div><h3>{{ Auth::user()->name }}</h3><p>Kênh của bạn</p></div>
  </div>
  <a href="/studio/content" class="item active">🎬 Nội dung</a>
  <a href="/studio/analytics" class="item">📈 Số liệu phân tích</a>
  <a href="/studio/community" class="item">👥 Cộng đồng</a>
  <a href="/studio/settings" class="item">⚙️ Cài đặt</a>
</aside>

<main class="content">
  <h2>Nội dung của kênh</h2>
  <div class="tabs">
    <span>Nguồn cảm hứng</span>
    <span class="active">Video</span>
    <span>Shorts</span>
    <span>Sự kiện phát trực tiếp</span>
    <span>Bài đăng</span>
    <span>Danh sách phát</span>
    <span>Podcast</span>
    <span>Quảng bá</span>
  </div>

  <div class="filter">☰ Lọc</div>

  <table id="videoTable">
    <thead>
      <tr>
        <th></th>
        <th>Video</th>
        <th>Chế độ hiển thị</th>
        <th>Hạn chế</th>
        <th>Ngày</th>
        <th>Lượt xem</th>
        <th>Số bình luận</th>
        <th>Lượt thích</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      @foreach($videos as $video)
      <tr>
        <td><input type="checkbox"></td>
        <td class="video-cell">
 <img src="{{ asset('storage/' . $video->thumbnail) }}" class="thumb">
          <div>
            <p class="title">{{ $video->title }}</p>
            <small>{{ $video->description }}</small>
          </div>
        </td>
        <td> Công khai</td>
        <td>Không</td>
        <td>{{ $video->created_at->format('d/m/Y') }}</td>
        <td>{{ $video->views ?? 0 }}</td>
        <td>0</td>
        <td>100%</td>
        <td>
  <button onclick='openEditModal(
    {{ $video->id }},
    @json($video->title),
    @json($video->description)
)'>✏️</button>

  <form action="{{ route('studio.video.destroy', $video->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Xóa video này?')">🗑️</button>
</form>
</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</main>
</div>

<!-- MODAL UPLOAD -->
<!-- MODAL UPLOAD -->
<div id="uploadModal" class="upload-modal">
  <div class="upload-box">
    <span class="close" onclick="closeUploadModal()">✖</span>
    <h2>Tải video lên</h2>

    <form action="{{ route('studio.upload') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="file" name="video" required><br><br>
      <input type="text" name="title" placeholder="Tiêu đề" required><br><br>
      <textarea name="description" placeholder="Mô tả"></textarea><br><br>
      <button type="submit">Tải lên</button>
    </form>

    <p class="note">Video sẽ ở chế độ riêng tư cho đến khi bạn xuất bản.</p>
  </div>
</div>

<div id="editModal" class="upload-modal">
  <div class="upload-box">
    <span class="close" onclick="closeEditModal()">✖</span>
    <h2>Sửa video</h2>

    <form id="editForm" method="POST">
  @csrf
  @method('PUT')
  <input type="text" name="title" id="editTitle" placeholder="Tiêu đề"><br><br>
  <textarea name="description" id="editDesc" placeholder="Mô tả"></textarea><br><br>
  <button type="submit">Cập nhật</button>
</form>
  </div>
</div>

<script>
// ====== MỞ MODAL ======
function openUploadModal() {
    document.getElementById('uploadModal').style.display = 'block';
}

// ====== ĐÓNG MODAL ======
function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
}

// ====== CLICK RA NGOÀI ĐỂ ĐÓNG ======
window.onclick = function(event) {
    let modal = document.getElementById('uploadModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
// -------- AVATAR MENU --------
function toggleAvatarMenu() {
    const menu = document.getElementById("avatarMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// Click ngoài menu để đóng
document.addEventListener("click", function(event) {
    const menu = document.getElementById("avatarMenu");
    const avatar = document.querySelector('.avatar-img');

    if (!menu || !avatar) return;

    if (!menu.contains(event.target) && !avatar.contains(event.target)) {
        menu.style.display = "none";
    }
});

// Ngăn click avatar bubble lên document
document.querySelector('.avatar-img')?.addEventListener('click', function(e) {
    e.stopPropagation();
    toggleAvatarMenu();
});
function openEditModal(id, title, desc) {
    const modal = document.getElementById('editModal');
    modal.style.display = 'block';

    const form = document.getElementById('editForm');
    form.action = `/studio/video/${id}`; // Đúng route PUT + id

    document.getElementById('editTitle').value = title;
    document.getElementById('editDesc').value = desc;
}


function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
</body>
</html>