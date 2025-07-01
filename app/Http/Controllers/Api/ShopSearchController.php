<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Services\ShopSearchService;
use App\Services\GooglePlacesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class ShopSearchController extends Controller
{
    private ShopSearchService $shopSearchService;
    private GooglePlacesService $googlePlacesService;

    public function __construct(
        ShopSearchService $shopSearchService,
        GooglePlacesService $googlePlacesService
    ) {
        $this->shopSearchService = $shopSearchService;
        $this->googlePlacesService = $googlePlacesService;
    }

    /**
     * 店舗名で検索（統合検索サービス使用）
     * - バリデーションエラー時は422で返す
     * - 空文字や2文字未満のクエリの場合は空配列を返す
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('query', '');
            $language = $request->input('language', 'ja');
            $includePostCount = $request->boolean('include_post_count', false);

            // 2文字未満や空文字の場合は空配列を返す（APIコスト・UX最適化）
            if (mb_strlen(trim($query)) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'count' => 0
                ]);
            }

            // バリデーション
            $request->validate([
                'query' => 'required|string|min:2|max:100',
                'language' => 'string|in:ja,en',
                'include_post_count' => 'boolean',
            ]);

            // 統合検索サービスを使用
            $results = $this->shopSearchService->searchShops($query, $language, $includePostCount);

            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // バリデーションエラー時は422で返す
            return response()->json([
                'success' => false,
                'message' => '検索条件が不正です。',
                'error' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Shop search error', [
                'query' => $request->input('query'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => '検索中にエラーが発生しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 店舗詳細情報の取得（Google Places API）
     */
    public function getPlaceDetails(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'place_id' => 'required|string',
                'language' => 'string|in:ja,en',
            ]);

            $placeId = $request->input('place_id');
            $language = $request->input('language', 'ja');

            $details = $this->googlePlacesService->getPlaceDetailsNew($placeId, $language);

            if ($details) {
                return response()->json([
                    'success' => true,
                    'data' => $details
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '店舗詳細情報が見つかりませんでした。'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('Place details error', [
                'place_id' => $request->input('place_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => '店舗詳細情報の取得に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 店舗選択の検証（投稿作成画面用）
     */
    public function validateSelection(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'selected_shop' => 'required|array',
                'selected_shop.name' => 'required|string',
                'selected_shop.address' => 'required|string',
            ]);

            $selectedShop = $request->input('selected_shop');
            $isValid = $this->shopSearchService->validateShopSelection($selectedShop);

            if ($isValid) {
                return response()->json([
                    'success' => true,
                    'message' => '店舗選択が有効です。'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => '店舗を候補から選択してください。',
                    'errors' => [
                        'shop_selection' => '店舗を候補から選択してください。'
                    ]
                ], 422);
            }
        } catch (Exception $e) {
            Log::error('Shop selection validation error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => '店舗選択の検証に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
