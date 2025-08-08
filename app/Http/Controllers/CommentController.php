<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

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
                'level' => $comment->parent_id ? 1 : 0, // 親コメントがあれば階層は1
            ])->render();

            // コメント数を取得
            $commentCount = Comment::where('post_id', $post->id)->count();

            // 4. JSONレスポンスを返す
            return response()->json([
                'html' => $commentComponent, // 生成したHTML
                'message' => 'コメントを投稿しました',
                'comment_count' => $commentCount,
                'parent_id' => $comment->parent_id, // どのコメントへの返信か
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
            if (request()->expectsJson()) {
                return response()->json(['message' => '削除権限がありません'], 403);
            }
            abort(403, '削除権限がありません。');
        }

        $postId = $comment->post_id;
        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'コメントを削除しました',
            ]);
        }

        return redirect()->route('posts.show', $postId)
            ->with('success', 'コメントを削除しました。');
    }
}
