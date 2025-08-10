<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request, Post $post)
    {
        try {
            DB::beginTransaction();

            // バリデーション済みデータを元に、コメントインスタンスを作成
            $comment = new Comment($request->validated());
            $comment->user_id = Auth::id();
            $comment->post_id = $post->id;
            $comment->save();

            DB::commit();

            // AJAXリクエストかどうかを判定
            if ($request->expectsJson()) {
                // 新しいコメントのHTMLを生成
                $commentComponent = view('components.molecules.comment-card', [
                    'comment' => $comment->load('user'),
                    'post' => $post,
                    'level' => $comment->parent_id ? 1 : 0,
                ])->render();

                // コメント数を取得
                $commentCount = $post->comments()->whereNull('parent_id')->count();

                // JSONレスポンスを返す
                return response()->json([
                    'html' => $commentComponent,
                    'message' => 'コメントを投稿しました',
                    'comment_count' => $commentCount,
                    'parent_id' => $comment->parent_id,
                ], 201);
            }

            // 通常のリクエスト（非AJAX）の場合
            return redirect()->route('posts.show', $post->id)
                ->with('success', 'コメントを投稿しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('コメント投稿エラー: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'コメントの投稿に失敗しました。',
                ], 500);
            }

            return back()->withErrors(['error' => 'コメントの投稿に失敗しました。']);
        }
    }

    public function destroy(Comment $comment)
    {
        // 権限チェック
        if (Auth::id() !== $comment->user_id && Auth::id() !== $comment->post->user_id) {
            if (request()->expectsJson()) {
                return response()->json(['message' => '削除権限がありません'], 403);
            }
            abort(403, '削除権限がありません。');
        }

        try {
            DB::beginTransaction();

            $postId = $comment->post_id;

            // 子コメントも含めて削除（カスケード削除）
            $comment->children()->delete();
            $comment->delete();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'コメントを削除しました',
                ], 200);
            }

            return redirect()->route('posts.show', $postId)
                ->with('success', 'コメントを削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('コメント削除エラー: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'comment_id' => $comment->id,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'コメントの削除に失敗しました。',
                ], 500);
            }

            return back()->withErrors(['error' => 'コメントの削除に失敗しました。']);
        }
    }
}
