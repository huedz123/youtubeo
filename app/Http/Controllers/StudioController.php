<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class StudioController extends Controller
{
    // Cập nhật video
    public function update(Request $request, $id)
    {
        $video = Video::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $video->title = $request->title;
        $video->description = $request->description;
        $video->save();

        return response()->json([
            'success' => true,
            'title' => $video->title,
            'description' => $video->description,
        ]);
    }

    // Xóa video
    public function destroy($id)
    {
        $video = Video::where('user_id', Auth::id())->findOrFail($id);

        // ✅ FIX ĐÚNG ĐƯỜNG DẪN
        if ($video->video_path) {
            $file = public_path($video->video_path);

            if (file_exists($file)) {
                unlink($file);
            }
        }

        $video->delete();

        return response()->json(['success' => true]);
    }
}