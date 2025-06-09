<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;  // ★ 修正: use文を追加
use App\Models\Shop;
use App\Http\Requests\ShopFavoriteRequest;

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
     * 店舗をお気に入りに追加（AJAX専用）
     * 
     * @param ShopFavoriteRequest $request
     * @param Shop $shop
     * @return JsonResponse
     */
    public function store(ShopFavoriteRequest $request, Shop $shop): JsonResponse
    {
        try {
            // AJAXリクエストチェック
            if (!$request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AJAX request required'
                ], 400);
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 既にお気に入りに登録済みかチェック
            if ($user->hasFavoriteShop($shop->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'この店舗は既にお気に入りに登録されています',
                    'is_favorited' => true,
                    'favorites_count' => $shop->favorites_count
                ], 409); // 409 Conflict
            }

            // お気に入りに追加
            $user->addFavoriteShop($shop->id);

            // 更新されたお気に入り数を取得
            $shop->refresh();
            $favoritesCount = $shop->favorited_by_users()->count();

            return response()->json([
                'success' => true,
                'message' => 'お気に入りに追加しました',
                'is_favorited' => true,
                'favorites_count' => $favoritesCount
            ]);
        } catch (\Exception $e) {
            Log::error('Favorite shop store error: ' . $e->getMessage(), [  // ★ 修正: \Log を Log に変更
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'お気に入りの追加に失敗しました。しばらく時間をおいてから再度お試しください。'
            ], 500);
        }
    }

    /**
     * 店舗をお気に入りから削除（AJAX専用）
     * 
     * @param Request $request
     * @param Shop $shop
     * @return JsonResponse
     */
    public function destroy(Request $request, Shop $shop): JsonResponse
    {
        try {
            // AJAXリクエストチェック
            if (!$request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AJAX request required'
                ], 400);
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // お気に入りに登録されていないかチェック
            if (!$user->hasFavoriteShop($shop->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'この店舗はお気に入りに登録されていません',
                    'is_favorited' => false,
                    'favorites_count' => $shop->favorites_count
                ], 409); // 409 Conflict
            }

            // お気に入りから削除
            $user->removeFavoriteShop($shop->id);

            // 更新されたお気に入り数を取得
            $shop->refresh();
            $favoritesCount = $shop->favorited_by_users()->count();

            return response()->json([
                'success' => true,
                'message' => 'お気に入りから削除しました',
                'is_favorited' => false,
                'favorites_count' => $favoritesCount
            ]);
        } catch (\Exception $e) {
            Log::error('Favorite shop destroy error: ' . $e->getMessage(), [  // ★ 修正: \Log を Log に変更
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'お気に入りの削除に失敗しました。しばらく時間をおいてから再度お試しください。'
            ], 500);
        }
    }

    /**
     * お気に入り状態を取得（AJAX専用）
     * 
     * @param Request $request
     * @param Shop $shop
     * @return JsonResponse
     */
    public function status(Request $request, Shop $shop): JsonResponse
    {
        try {
            // AJAXリクエストチェック
            if (!$request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AJAX request required'
                ], 400);
            }

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $isFavorited = $user ? $user->hasFavoriteShop($shop->id) : false;
            $favoritesCount = $shop->favorited_by_users()->count();

            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'favorites_count' => $favoritesCount
            ]);
        } catch (\Exception $e) {
            Log::error('Favorite shop status error: ' . $e->getMessage(), [  // ★ 修正: \Log を Log に変更
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'お気に入り状態の取得に失敗しました'
            ], 500);
        }
    }
}
