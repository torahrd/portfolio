<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class UserSearchController extends Controller
{
    /**
     * メンション用のユーザー検索（返信時のみ）
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $commentId = $request->get('comment_id');

        // デバッグログ
        Log::info('User search request', [
            'query' => $query,
            'comment_id' => $commentId,
            'user_id' => auth()->id()
        ]);

        if (strlen($query) < 1) {
            Log::info('Query too short');
            return response()->json([]);
        }

        // comment_idが指定されていない場合は全ユーザーから検索（デバッグ用）
        if (!$commentId) {
            Log::info('No comment_id provided, searching all users');
            $users = User::where('name', 'LIKE', $query . '%')
                ->where('id', '!=', auth()->id()) // 自分以外
                ->select('id', 'name')
                ->limit(10)
                ->get();

            Log::info('All users search result', ['count' => $users->count()]);
            return response()->json($users);
        }

        // 返信するコメントスレッド内のユーザーを取得
        $userIds = $this->getSpecificThreadUsers($commentId);

        Log::info('Thread users found', [
            'comment_id' => $commentId,
            'user_ids' => $userIds
        ]);

        if (empty($userIds)) {
            Log::info('No users in thread');
            return response()->json([]);
        }

        $users = User::whereIn('id', $userIds)
            ->where('name', 'LIKE', $query . '%')
            ->where('id', '!=', auth()->id()) // 自分は除外
            ->select('id', 'name')
            ->limit(10)
            ->get();

        Log::info('Final search result', [
            'query' => $query,
            'matched_users' => $users->count(),
            'users' => $users->pluck('name')->toArray()
        ]);

        return response()->json($users);
    }

    /**
     * 特定のコメントスレッドのユーザーを取得
     */
    private function getSpecificThreadUsers($commentId)
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            Log::warning('Comment not found', ['comment_id' => $commentId]);
            return [];
        }

        // 元コメント（最上位の親）を特定
        $rootComment = $comment;
        while ($rootComment->parent_id) {
            $rootComment = $rootComment->parent;
        }

        Log::info('Root comment found', [
            'root_comment_id' => $rootComment->id,
            'root_user_id' => $rootComment->user_id
        ]);

        // そのスレッドに参加している全ユーザーを取得
        $userIds = collect();

        // 元コメントの投稿者
        $userIds->push($rootComment->user_id);

        // 投稿者も追加
        $userIds->push($rootComment->post->user_id);

        // 元コメント配下の全返信の投稿者
        $replies = Comment::where('post_id', $rootComment->post_id)
            ->where(function ($query) use ($rootComment) {
                $this->addDescendantConditions($query, $rootComment->id);
            })
            ->pluck('user_id');

        Log::info('Replies found', [
            'reply_user_ids' => $replies->toArray()
        ]);

        $userIds = $userIds->merge($replies)->unique()->values();

        Log::info('All thread participants', [
            'user_ids' => $userIds->toArray()
        ]);

        return $userIds->toArray();
    }

    /**
     * 子孫コメントの条件を再帰的に追加
     */
    private function addDescendantConditions($query, $parentId)
    {
        $query->orWhere('parent_id', $parentId);

        $children = Comment::where('parent_id', $parentId)->pluck('id');
        foreach ($children as $childId) {
            $this->addDescendantConditions($query, $childId);
        }
    }
}
