<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Comment;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\VideoUserVote;
use Illuminate\Support\Facades\Cache;


class VideoController extends Controller
{
    // Trang home hiển thị tất cả video
    public function index() {
     $videos = Video::latest()->paginate(12); 
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

    $file = $request->file('video');

    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

    $path = $file->storeAs('videos', $filename, 'public');

    $videoPath = storage_path('app/public/' . $path);

    // 👉 chuẩn bị thumbnail (CHƯA tạo ngay)
    $thumbName = Str::uuid() . '.jpg';
    $thumbRelativePath = 'thumbnails/' . $thumbName;
    $thumbPath = storage_path('app/public/' . $thumbRelativePath);

    // tạo folder nếu chưa có
    if (!Storage::disk('public')->exists('thumbnails')) {
        Storage::disk('public')->makeDirectory('thumbnails');
    }

$command = "ffmpeg -i \"$videoPath\" -ss 00:00:01 -vframes 1 \"$thumbPath\" 2>&1";
exec($command);
    // 👉 lưu DB (có thể chưa có ảnh ngay)
    $video = Video::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description' => $request->description,
        'category' => $request->category ?? 'Khác',
        'video_path' => $path,
     'thumbnail' => $thumbRelativePath,
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

    // Lấy video khác, 10 bản ghi mỗi trang
    $videos = Video::where('id', '!=', $id)
                   ->latest()
                   ->paginate(10);

    $userVote = null;

if (auth()->check()) {
    $vote = VideoUserVote::where('video_id', $id)
        ->where('user_id', auth()->id())
        ->first();

    $userVote = $vote ? $vote->type : null;
}

return view('video_detail', compact('video', 'videos', 'userVote'));
    }

    public function like($id)
{
    $video = Video::findOrFail($id);
    $userId = auth()->id();

    $vote = VideoUserVote::where('video_id', $id)
    ->where('user_id', $userId)
    ->first();


    if (!$vote) {
        // chưa vote → tạo like
        VideoUserVote::create([
            'video_id' => $id,
            'user_id' => $userId,
            'type' => 'like'
        ]);
    } else {
        if ($vote->type === 'like') {
            // đã like → bỏ like
            $vote->delete();
        } else {
            // đang dislike → chuyển sang like
            $vote->update(['type' => 'like']);
        }
    }

    // đếm lại
    $likes = VideoUserVote::where('video_id', $id)->where('type', 'like')->count();
    $dislikes = VideoUserVote::where('video_id', $id)->where('type', 'dislike')->count();

 $newVote = VideoUserVote::where('video_id', $id)
    ->where('user_id', $userId)
    ->first();

$userVote = $newVote ? $newVote->type : null;

return response()->json([
    'success' => true,
    'likes' => $likes,
    'dislikes' => $dislikes,
    'user_vote' => $userVote
]);
}

    public function dislike($id)
{
    $video = Video::findOrFail($id);
    $userId = auth()->id();

    $vote = VideoUserVote::where('video_id', $id)
    ->where('user_id', $userId)
    ->first();

    if (!$vote) {
        VideoUserVote::create([
            'video_id' => $id,
            'user_id' => $userId,
            'type' => 'dislike'
        ]);
    } else {
        if ($vote->type === 'dislike') {
            $vote->delete();
        } else {
            $vote->update(['type' => 'dislike']);
        }
    }

    $likes = VideoUserVote::where('video_id', $id)->where('type', 'like')->count();
    $dislikes = VideoUserVote::where('video_id', $id)->where('type', 'dislike')->count();

 $newVote = VideoUserVote::where('video_id', $id)
    ->where('user_id', $userId)
    ->first();

$userVote = $newVote ? $newVote->type : null;

return response()->json([
    'success' => true,
    'likes' => $likes,
    'dislikes' => $dislikes,
    'user_vote' => $userVote
]);
}

public function destroy($id)
{
    $video = \App\Models\Video::findOrFail($id);

    // kiểm tra quyền (chỉ chủ video được xóa)
    if ($video->user_id != auth()->id()) {
        abort(403);
    }

    // xóa file video nếu có (optional)
   if ($video->video_path) {
    Storage::disk('public')->delete($video->video_path);
}

if ($video->thumbnail && $video->thumbnail !== 'default.jpg') {
    Storage::disk('public')->delete($video->thumbnail);
}

    // xóa DB
    $video->delete();

    return redirect()->back()->with('success', 'Đã xóa video');
}
public function postComment(Request $request, $id)
{
    $request->validate([
        'content' => 'required|string|max:1000'
    ]);

    Comment::create([
        'user_id' => auth()->id(),
        'video_id' => $id,
        'content' => $request->content
    ]);

    return back();
}
}