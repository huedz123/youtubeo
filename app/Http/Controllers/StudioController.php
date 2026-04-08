<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller
{
    // Cập nhật video
    public function update(Request $request, $id)
    {
        
       $video = Video::findOrFail($id);

   // Kiểm tra quyền sở hữu
   if ($video->user_id != auth()->id()) {
       abort(403);
   }

   $request->validate([
       'title' => 'required|string|max:255',
       'description' => 'nullable|string',
   ]);

   $video->update([
       'title' => $request->title,
       'description' => $request->description,
   ]);

   return back()->with('success', 'Cập nhật thành công');
    }

    // Xóa video
    public function destroy($id)
    {
       $video = Video::findOrFail($id);

    if ($video->user_id != auth()->id()) {
        abort(403);
    }

    // Xóa file video + thumbnail
    if ($video->video_path) {
        Storage::disk('public')->delete($video->video_path);
    }
    if ($video->thumbnail && $video->thumbnail !== 'default.jpg') {
        Storage::disk('public')->delete($video->thumbnail);
    }

    $video->delete();

    return back()->with('success', 'Video đã được xóa');
    }
}