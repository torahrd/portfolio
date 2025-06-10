<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowController extends Controller
{
    /**
     * フォロー/アンフォロー処理
     */
    public function follow(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // 自分自身をフォローできない
        if ($currentUser->id === $user->id) {
            return response()->json([
                'error' => '自分自身をフォローすることはできません'
            ], 400);
        }

        // 既にフォロー中の場合はアンフォロー
        if ($currentUser->isFollowing($user)) {
            $currentUser->unfollow($user);

            return response()->json([
                'success' => true,
                'is_following' => false,
                'message' => $user->name . 'のフォローを解除しました',
                'followers_count' => $user->fresh()->followers_count
            ]);
        }

        // フォロー処理
        $currentUser->follow($user);

        // 通知処理（将来実装予定）
        if ($user->is_private) {
            // プライベートアカウントの場合は申請通知
            // $user->notify(new \App\Notifications\NewFollowRequest($currentUser));
            $message = $user->name . 'にフォロー申請を送信しました';
        } else {
            // パブリックアカウントの場合はフォロー完了通知
            // $user->notify(new NewFollower($currentUser));
            $message = $user->name . 'をフォローしました';
        }

        return response()->json([
            'success' => true,
            'is_following' => true,
            'is_pending' => $user->is_private,
            'message' => $message,
            'followers_count' => $user->fresh()->followers_count
        ]);
    }

    /**
     * フォロー申請の承認
     */
    public function acceptFollowRequest(Request $request, User $user)
    {
        $currentUser = auth()->user();

        if (!$currentUser->hasPendingFollowRequest($user)) {
            return response()->json([
                'error' => 'フォロー申請が見つかりません'
            ], 404);
        }

        $currentUser->acceptFollowRequest($user);

        // 承認通知を送信（将来実装予定）
        // $user->notify(new FollowRequestAccepted($currentUser));

        return response()->json([
            'success' => true,
            'message' => $user->name . 'のフォロー申請を承認しました'
        ]);
    }

    /**
     * フォロー申請の拒否
     */
    public function rejectFollowRequest(Request $request, User $user)
    {
        $currentUser = auth()->user();

        if (!$currentUser->hasPendingFollowRequest($user)) {
            return response()->json([
                'error' => 'フォロー申請が見つかりません'
            ], 404);
        }

        $currentUser->rejectFollowRequest($user);

        return response()->json([
            'success' => true,
            'message' => $user->name . 'のフォロー申請を拒否しました'
        ]);
    }

    /**
     * フォロワー一覧
     */
    public function followers(Request $request, User $user)
    {
        $followers = $user->followers()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('profile.followers', compact('user', 'followers'));
    }

    /**
     * フォロー中一覧
     */
    public function following(Request $request, User $user)
    {
        $following = $user->following()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('profile.following', compact('user', 'following'));
    }
}
