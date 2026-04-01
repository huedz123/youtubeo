<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Comment;

class VideoController extends Controller
{
    // Trang home hiển thị tất cả video
    public function index() {
        $videos = Video::latest()->get();
        return view('home', compact('videos'));
    }

    // Trang studio của user
    public function showForm() {
        $videos = Video::where('user_id', auth()->id())->latest()->get();
        return view('studio', compact('videos'));
    }

    // Upload video
    public function upload(Request $request) {
        $request->validate([
            'title' => 'required',
            'video' => 'required|file|mimes:mp4,mov,avi|max:102400'
        ]);

        // Upload file video
        $file = $request->file('video');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('videos'), $filename);

        // ====== TẠO THUMBNAIL ======
        $thumbName = time() . '.jpg';
        $thumbPath = public_path('thumbnails/' . $thumbName);
        $videoPath = public_path('videos/' . $filename);

        // Dùng FFmpeg cắt frame tại giây thứ 2
        exec("ffmpeg -i \"$videoPath\" -ss 00:00:02 -vframes 1 \"$thumbPath\"");

        // Nếu không tạo được thì dùng ảnh mặc định
        if (!file_exists($thumbPath)) {
            $thumbName = 'default.jpg';
        }

        // Lưu database
        $video = Video::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => 'videos/'.$filename,
            'thumbnail' => $thumbName,
            'views' => 0
        ]);

        return redirect()->back()->with('success', 'Upload thành công!');
    }

    // Trang chi tiết video
    public function show($id)
    {
        $video = Video::with('user', 'comments.user')->findOrFail($id);

        // Tăng view
        $video->increment('views');

        $videos = Video::latest()->get();

        return view('video_detail', compact('video', 'videos'));
    }
    public function like($id)
{
    $video = Video::findOrFail($id);

    $video->increment('likes');

    return response()->json([
        'likes' => $video->likes
    ]);
}

public function dislike($id)
{
    $video = Video::findOrFail($id);

    $video->increment('dislikes');

    return response()->json([
        'dislikes' => $video->dislikes
    ]);
}
}