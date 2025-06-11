<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FollowRequestController extends Controller
{
    /**
     * フォロー申請一覧の取得
     */
    public function index(Request $request): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            // 自分に対するフォロー申請を取得
            $requests = $user->pendingFollowRequests()
                ->with(['pivot'])
                ->orderBy('followers.created_at', 'desc')
                ->get()
                ->map(function ($requester) {
                    return [
                        'id' => $requester->id,
                        'user' => [
                            'id' => $requester->id,
                            'name' => $requester->name,
                            'avatar_url' => $requester->avatar_url,
                            'bio' => $requester->bio,
                            'is_private' => $requester->is_private
                        ],
                        'created_at' => $requester->pivot->created_at,
                        'status' => $requester->pivot->status
                    ];
                });

            Log::info('フォロー申請一覧取得', [
                'user_id' => $user->id,
                'requests_count' => $requests->count()
            ]);

            return response()->json([
                'success' => true,
                'requests' => $requests
            ]);
        } catch (\Exception $e) {
            Log::error('フォロー申請一覧取得エラー', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'フォロー申請の取得に失敗しました'
            ], 500);
        }
    }

    /**
     * フォロー申請数の取得
     */
    public function count(Request $request): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            $count = $user->pendingFollowRequests()->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('フォロー申請数取得エラー', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'フォロー申請数の取得に失敗しました'
            ], 500);
        }
    }

    /**
     * フォロー申請を既読にする（将来実装予定）
     */
    public function markAsRead(Request $request): JsonResponse
    {
        try {
            // 通知機能実装時に詳細を実装予定
            return response()->json([
                'success' => true,
                'message' => '既読機能は実装予定です'
            ]);
        } catch (\Exception $e) {
            Log::error('フォロー申請既読エラー', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => '既読処理に失敗しました'
            ], 500);
        }
    }
}
