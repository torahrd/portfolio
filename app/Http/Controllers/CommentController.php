<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\CommentStoreRequest;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request, Post $post)
    {
        // 1. バリデーション済みデータを元に、コメントインスタンスを作成
        $comment = new Comment($request->validated());
        $comment->user_id = Auth::id(); // ログイン中のユーザーIDを設定
        $comment->post_id = $post->id;   // 対象の投稿IDを設定
        $comment->save();                // データベースに保存

        // 2. AJAXリクエストかどうかを判定
        if ($request->expectsJson()) {
            // 3. 新しいコメントのHTMLを生成
            $commentComponent = view('components.molecules.comment-card', [
                'comment' => $comment->load('user'), // ユーザー情報も一緒に読み込む
                'post' => $post,
                'level' => $comment->parent_id ? 1 : 0 // 親コメントがあれば階層は1
            ])->render();

            // 4. JSONレスポンスを返す
            return response()->json([
                'html' => $commentComponent, // 生成したHTML
                'parent_id' => $comment->parent_id // どのコメントへの返信か
            ]);
        }

        // 5. 通常のリクエスト（非AJAX）の場合
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
