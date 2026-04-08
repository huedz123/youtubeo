function toggleMenu() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("active");
  sidebar.classList.toggle("collapsed");
}

function searchVideo() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const videos = document.querySelectorAll(".video-card");

  videos.forEach(video => {
    const title = video.querySelector("h4").innerText.toLowerCase();

    if (title.includes(keyword)) {
      video.style.display = "block";
    } else {
      video.style.display = "none";
    }
  });
}

function goToLogin() {
  window.location.href = "/login";
}

function uploadVideo() {
  window.location.href = "/upload"; 
  input.type = 'file';
  input.accept = 'video/*';
  input.onchange = e => {
    const file = e.target.files[0];
    if (file) {
      alert("Đã chọn video: " + file.name);
      // TODO: xử lý upload lên server
    }
  };
  input.click();
}

function likeVideo(id) {
    fetch(`/video/${id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('like-count').textContent = data.likes;
            document.getElementById('dislike-count').textContent = data.dislikes;

            updateButtons(data.user_vote);
        }
    });
}

function dislikeVideo(id) {
    fetch(`/video/${id}/dislike`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('like-count').textContent = data.likes;
            document.getElementById('dislike-count').textContent = data.dislikes;

            updateButtons(data.user_vote);
        }
    });
}

function postComment(e, id) {
  e.preventDefault();

  let comment = document.querySelector('textarea[name="comment"]').value;

  fetch(`/video/${id}/comment`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ comment: comment })
  })
  .then(res => {
    if (res.status === 401) {
      alert("Phải đăng nhập!");
      return;
    }
    return res.json();
  })
  .then(data => {
    if (data && data.success) {
      location.reload(); // reload để hiện comment mới
    }
  });
}

/* ----------- AVATAR MENU ----------- */
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
    document.getElementById('editModal').style.display = 'block';

    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDesc').value = desc;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function filterCategory(type) {
  const videos = document.querySelectorAll('.video-card');

  videos.forEach(video => {
    const category = video.getAttribute('data-category');

    if (type === 'all' || category === type) {
      video.style.display = '';
    } else {
      video.style.display = 'none';
    }
  });
}



function updateButtons(vote) {
    const likeBtn = document.getElementById('likeBtn');
    const dislikeBtn = document.getElementById('dislikeBtn');

    // reset
    likeBtn.classList.remove('active');
    dislikeBtn.classList.remove('active');

    if (vote === 'like') {
        likeBtn.classList.add('active');
    } else if (vote === 'dislike') {
        dislikeBtn.classList.add('active');
    }
}