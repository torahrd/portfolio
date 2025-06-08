<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;

class UserSearchController extends Controller
{
    /**
     * メンション用のユーザー検索（返信時のみ）
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $commentId = $request->get('comment_id');

        if (strlen($query) < 1 || !$commentId) {
            return response()->json([]);
        }

        // 返信するコメントスレッド内のユーザーを取得
        $userIds = $this->getSpecificThreadUsers($commentId);

        if (empty($userIds)) {
            return response()->json([]);
        }

        $users = User::whereIn('id', $userIds)
            ->where('name', 'LIKE', $query . '%')
            ->select('id', 'name')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * 特定のコメントスレッドのユーザーを取得
     */
    private function getSpecificThreadUsers($commentId)
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            return [];
        }

        // 元コメント（最上位の親）を特定
        $rootComment = $comment;
        while ($rootComment->parent_id) {
            $rootComment = $rootComment->parent;
        }

        // そのスレッドに参加している全ユーザーを取得
        $userIds = collect();

        // 元コメントの投稿者
        $userIds->push($rootComment->user_id);

        // 元コメント配下の全返信の投稿者
        $replies = Comment::where('post_id', $rootComment->post_id)
            ->where(function ($query) use ($rootComment) {
                $this->addDescendantConditions($query, $rootComment->id);
            })
            ->pluck('user_id');

        $userIds = $userIds->merge($replies)->unique()->values();

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
