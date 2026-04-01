<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Tải video lên</title>
<style>
.upload-box {
  max-width: 500px;
  margin: auto;
  background: white;
  padding: 20px;
  border-radius: 10px;
}
input, textarea {
  width: 100%;
  margin-top: 10px;
  padding: 8px;
}
button {
  margin-top: 15px;
  padding: 10px;
  background: red;
  color: white;
  border: none;
}
</style>
</head>
<body>

<div class="upload-box">
  <h2>Tải video lên</h2>

  <form action="/upload" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="file" name="video" required>

    <input type="text" name="title" placeholder="Tiêu đề" required>

    <textarea name="description" placeholder="Mô tả"></textarea>

    <button type="submit">Đăng</button>
  </form>
</div>

</body>
</html>