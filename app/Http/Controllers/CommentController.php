<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'video_id' => $id,
            'content' => $request->comment
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
