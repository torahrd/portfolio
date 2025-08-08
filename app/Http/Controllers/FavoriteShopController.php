<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteShopController extends Controller
{
    /**
     * コンストラクタ - 認証必須
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 店舗をお気に入りに追加（POST）
     */
    public function store(Request $request, Shop $shop): JsonResponse
    {
        try {
            Log::info('お気に入り追加リクエスト開始', [
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'request_method' => $request->method(),
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 既にお気に入りに登録済みかチェック
            if ($user->hasFavoriteShop($shop->id)) {
                Log::warning('既にお気に入り登録済み', [
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'この店舗は既にお気に入りに登録されています',
                    'is_favorited' => true,
                    'favorites_count' => $shop->favorited_by_users()->count(),
                ], 409);
            }

            // お気に入りに追加
            $user->addFavoriteShop($shop->id);

            // 更新されたお気に入り数を取得
            $favoritesCount = $shop->favorited_by_users()->count();

            Log::info('お気に入り追加成功', [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'new_favorites_count' => $favoritesCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'お気に入りに追加しました',
                'is_favorited' => true,
                'favorites_count' => $favoritesCount,
            ], 200);
        } catch (\Exception $e) {
            Log::error('お気に入り追加エラー', [
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'お気に入りの追加に失敗しました。しばらく時間をおいてから再度お試しください。',
            ], 500);
        }
    }

    /**
     * 店舗をお気に入りから削除（DELETE）
     */
    public function destroy(Request $request, Shop $shop): JsonResponse
    {
        try {
            Log::info('お気に入り削除リクエスト開始', [
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'request_method' => $request->method(),
                'headers' => $request->headers->all(),
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // お気に入りに登録されていないかチェック
            if (! $user->hasFavoriteShop($shop->id)) {
                Log::warning('お気に入り未登録での削除試行', [
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'この店舗はお気に入りに登録されていません',
                    'is_favorited' => false,
                    'favorites_count' => $shop->favorited_by_users()->count(),
                ], 404);
            }

            // お気に入りから削除
            $user->removeFavoriteShop($shop->id);

            // 更新されたお気に入り数を取得
            $favoritesCount = $shop->favorited_by_users()->count();

            Log::info('お気に入り削除成功', [
                'user_id' => $user->id,
                'shop_id' => $shop->id,
                'new_favorites_count' => $favoritesCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'お気に入りから削除しました',
                'is_favorited' => false,
                'favorites_count' => $favoritesCount,
            ], 200);
        } catch (\Exception $e) {
            Log::error('お気に入り削除エラー', [
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'お気に入りの削除に失敗しました。しばらく時間をおいてから再度お試しください。',
            ], 500);
        }
    }

    /**
     * お気に入り状態を取得（GET）
     */
    public function status(Request $request, Shop $shop): JsonResponse
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $isFavorited = $user ? $user->hasFavoriteShop($shop->id) : false;
            $favoritesCount = $shop->favorited_by_users()->count();

            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'favorites_count' => $favoritesCount,
            ], 200);
        } catch (\Exception $e) {
            Log::error('お気に入り状態取得エラー', [
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'お気に入り状態の取得に失敗しました',
            ], 500);
        }
    }
}
