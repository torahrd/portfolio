<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // $request->validate([
        //     'body' => 'required|max:200',
        //     'parent_id' => 'nullable|exists:comments,id'
        // ]);

        $input = [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ];

        Comment::create($input);

        return redirect()->route('posts.show', $post->id)
            ->with('success', 'コメントを投稿しました。');
    }

    public function destroy(Comment $comment)
    {
        // 自分のコメントまたは投稿の作者のみ削除可能
        if (Auth::id() !== $comment->user_id && Auth::id() !== $comment->post->user_id) {
            abort(403, '削除権限がありません。');
        }

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)
            ->with('success', 'コメントを削除しました。');
    }
}
