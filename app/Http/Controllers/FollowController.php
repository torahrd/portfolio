<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\FollowRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Notifications\FollowRequestNotification;

class FollowController extends Controller
{
    /**
     * フォロー/アンフォロー処理
     */
    public function follow(FollowRequest $request, User $user)
    {
        try {
            /** @var User $currentUser */
            $currentUser = $request->getAuthenticatedUser();
            $targetUser = $user;

            Log::info('フォロー処理開始', [
                'current_user_id' => $currentUser->id,
                'target_user_id' => $targetUser->id
            ]);

            // 既にフォロー中の場合はアンフォロー
            if ($currentUser->isFollowing($targetUser)) {
                $currentUser->unfollow($targetUser);

                Log::info('フォロー解除完了', [
                    'current_user_id' => $currentUser->id,
                    'target_user_id' => $targetUser->id
                ]);

                $message = $targetUser->name . 'のフォローを解除しました';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'is_following' => false,
                        'is_pending' => false,
                        'message' => $message,
                        'followers_count' => $targetUser->fresh()->followers_count
                    ]);
                } else {
                    return redirect()->back()->with('status', $message);
                }
            }

            // 申請中の場合は申請を取り消し
            if ($currentUser->hasSentFollowRequest($targetUser)) {
                $currentUser->unfollow($targetUser);

                Log::info('フォロー申請取り消し完了', [
                    'current_user_id' => $currentUser->id,
                    'target_user_id' => $targetUser->id
                ]);

                $message = $targetUser->name . 'への申請を取り消しました';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'is_following' => false,
                        'is_pending' => false,
                        'message' => $message,
                        'followers_count' => $targetUser->fresh()->followers_count
                    ]);
                } else {
                    return redirect()->back()->with('status', $message);
                }
            }

            // フォロー処理
            $currentUser->follow($targetUser);

            // プライベートアカウントの場合は申請通知を送信
            if ($targetUser->is_private) {
                $targetUser->notify(new FollowRequestNotification($currentUser));
            }

            $isPending = $targetUser->is_private;
            $message = $isPending
                ? $targetUser->name . 'にフォロー申請を送信しました'
                : $targetUser->name . 'をフォローしました';

            Log::info('フォロー処理完了', [
                'current_user_id' => $currentUser->id,
                'target_user_id' => $targetUser->id,
                'is_pending' => $isPending
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'is_following' => true,
                    'is_pending' => $isPending,
                    'message' => $message,
                    'followers_count' => $targetUser->fresh()->followers_count
                ]);
            } else {
                return redirect()->back()->with('status', $message);
            }
        } catch (\Exception $e) {
            Log::error('フォロー処理エラー', [
                'current_user_id' => $currentUser->id ?? null,
                'target_user_id' => $targetUser->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'フォロー処理に失敗しました。しばらく時間をおいてから再度お試しください。'
                ], 500);
            } else {
                return redirect()->back()->with('error', 'フォロー処理に失敗しました。しばらく時間をおいてから再度お試しください。');
            }
        }
    }

    /**
     * フォロー申請の承認
     */
    public function acceptFollowRequest(Request $request, User $user)
    {
        try {
            /** @var User $currentUser */
            $currentUser = auth()->user();

            if (!$currentUser->hasPendingFollowRequest($user)) {
                $message = 'フォロー申請が見つかりません';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 404);
                } else {
                    return redirect()->back()->with('error', $message);
                }
            }

            $currentUser->acceptFollowRequest($user);

            Log::info('フォロー申請承認', [
                'current_user_id' => $currentUser->id,
                'requester_user_id' => $user->id
            ]);

            $message = $user->name . 'のフォロー申請を承認しました';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                return redirect()->back()->with('status', $message);
            }
        } catch (\Exception $e) {
            Log::error('フォロー申請承認エラー', [
                'current_user_id' => auth()->id(),
                'requester_user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            $message = '申請の承認に失敗しました';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            } else {
                return redirect()->back()->with('error', $message);
            }
        }
    }

    /**
     * フォロー申請の拒否
     */
    public function rejectFollowRequest(Request $request, User $user)
    {
        try {
            /** @var User $currentUser */
            $currentUser = auth()->user();

            if (!$currentUser->hasPendingFollowRequest($user)) {
                $message = 'フォロー申請が見つかりません';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 404);
                } else {
                    return redirect()->back()->with('error', $message);
                }
            }

            $currentUser->rejectFollowRequest($user);

            Log::info('フォロー申請拒否', [
                'current_user_id' => $currentUser->id,
                'requester_user_id' => $user->id
            ]);

            $message = $user->name . 'のフォロー申請を拒否しました';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                return redirect()->back()->with('status', $message);
            }
        } catch (\Exception $e) {
            Log::error('フォロー申請拒否エラー', [
                'current_user_id' => auth()->id(),
                'requester_user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            $message = '申請の拒否に失敗しました';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            } else {
                return redirect()->back()->with('error', $message);
            }
        }
    }

    /**
     * フォロワー一覧
     */
    public function followers(Request $request, User $user)
    {
        // プライベートアカウントのアクセス制御
        /** @var User|null $currentUser */
        $currentUser = auth()->user();

        if (
            $user->is_private &&
            $currentUser?->id !== $user->id &&
            !$currentUser?->isFollowing($user)
        ) {
            abort(403, 'このユーザーのフォロワーリストは非公開です');
        }

        $followers = $user->followers()
            ->withPivot('created_at')
            ->orderBy('followers.created_at', 'desc')
            ->paginate(20);

        return view('profile.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    /**
     * フォロー中一覧
     */
    public function following(Request $request, User $user)
    {
        // プライベートアカウントのアクセス制御
        /** @var User|null $currentUser */
        $currentUser = auth()->user();

        if (
            $user->is_private &&
            $currentUser?->id !== $user->id &&
            !$currentUser?->isFollowing($user)
        ) {
            abort(403, 'このユーザーのフォローリストは非公開です');
        }

        $following = $user->following()
            ->withPivot('created_at')
            ->orderBy('followers.created_at', 'desc')
            ->paginate(20);

        return view('profile.followings', [
            'user' => $user,
            'followings' => $following,
        ]);
    }
}
