<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Services\GooglePlacesService;
use App\Services\TextNormalizationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class ShopSearchController extends Controller
{
    private GooglePlacesService $googlePlacesService;
    private TextNormalizationService $textNormalizationService;

    public function __construct(
        GooglePlacesService $googlePlacesService,
        TextNormalizationService $textNormalizationService
    ) {
        $this->googlePlacesService = $googlePlacesService;
        $this->textNormalizationService = $textNormalizationService;
    }

    /**
     * 店舗名で検索（Google Places APIベース + フォールバック）
     */
    public function search(Request $request): JsonResponse
    {
        try {
            // バリデーション
            $request->validate([
                'query' => 'required|string|min:2|max:100',
                'language' => 'string|in:ja,en',
            ]);

            $query = $request->input('query');
            $language = $request->input('language', 'ja');

            // 1. Google Places APIから検索（メイン）
            $results = $this->searchGooglePlaces($query, $language);

            // 2. 結果が少ない場合は既存DBからフォールバック
            if (count($results) < 3) {
                $fallbackResults = $this->searchExistingShopsFallback($query);
                $results = $this->mergeResultsOptimized($results, $fallbackResults);
            }

            // 3. 結果を5件に制限
            $results = array_slice($results, 0, 5);

            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ]);
        } catch (Exception $e) {
            Log::error('Shop search error', [
                'query' => $request->input('query'),
                'error' => $e->getMessage()
            ]);

            // エラー時は既存DB検索にフォールバック
            try {
                $fallbackResults = $this->searchExistingShopsFallback($query);
                $results = array_slice($fallbackResults, 0, 5);

                return response()->json([
                    'success' => true,
                    'data' => $results,
                    'count' => count($results),
                    'note' => 'Google Places APIが利用できませんでした。既存データから検索結果を表示しています。'
                ]);
            } catch (Exception $fallbackError) {
                Log::error('Fallback search also failed', [
                    'query' => $request->input('query'),
                    'error' => $fallbackError->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => '検索中にエラーが発生しました。',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }

    /**
     * Google Places APIから店舗を検索（メイン検索）
     */
    private function searchGooglePlaces(string $query, string $language): array
    {
        try {
            Log::info('Google Places API (New)検索開始', [
                'query' => $query,
                'language' => $language
            ]);

            // 新しいAPIを試行
            $places = $this->googlePlacesService->searchPlaceNew($query, $language);

            Log::info('Google Places API (New)検索結果', [
                'query' => $query,
                'result_count' => count($places)
            ]);

            // 新しいAPIのレスポンス形式に合わせて変換
            return $this->googlePlacesService->transformPlacesToShops($places, $query);
        } catch (\Exception $e) {
            Log::warning('Google Places API (New)検索失敗、フォールバック機能を使用', [
                'query' => $query,
                'error' => $e->getMessage(),
                'fallback_to_db' => true
            ]);

            // 新しいAPIが失敗した場合は空配列を返し、DB検索にフォールバック
            return [];
        }
    }

    /**
     * 既存データベースから店舗を検索（フォールバック用）
     */
    private function searchExistingShopsFallback(string $query): array
    {
        Log::debug('フォールバック検索開始', ['query' => $query]);
        try {
            $normalizedQuery = $this->textNormalizationService->normalizeShopName($query);

            // 完全一致
            $exact = Shop::where('name', $query);
            if ($normalizedQuery !== '' && $normalizedQuery !== $query) {
                $exact->orWhere('name', $normalizedQuery);
            }
            $exact = $exact->select(['id', 'name', 'address', 'formatted_phone_number', 'website', 'google_place_id', 'latitude', 'longitude'])->get();
            Log::debug('完全一致結果', $exact->toArray());

            // 前方一致
            $starts = Shop::where(function ($q) use ($query, $normalizedQuery) {
                $q->where('name', 'LIKE', "{$query}%");
                if ($normalizedQuery !== '' && $normalizedQuery !== $query) {
                    $q->orWhere('name', 'LIKE', "{$normalizedQuery}%");
                }
            })
                ->select(['id', 'name', 'address', 'formatted_phone_number', 'website', 'google_place_id', 'latitude', 'longitude'])
                ->get();
            Log::debug('前方一致結果', $starts->toArray());

            // 部分一致
            $partials = Shop::where(function ($q) use ($query, $normalizedQuery) {
                $q->where('name', 'LIKE', "%{$query}%");
                if ($normalizedQuery !== '' && $normalizedQuery !== $query) {
                    $q->orWhere('name', 'LIKE', "%{$normalizedQuery}%");
                }
            })
                ->select(['id', 'name', 'address', 'formatted_phone_number', 'website', 'google_place_id', 'latitude', 'longitude'])
                ->get();
            Log::debug('部分一致結果', $partials->toArray());

            // コレクションで重複排除しつつ優先度順に結合
            $all = collect();
            $addedIds = [];

            foreach ($exact as $shop) {
                if (!in_array($shop->id, $addedIds, true)) {
                    $all->push($shop);
                    $addedIds[] = $shop->id;
                }
            }
            foreach ($starts as $shop) {
                if (!in_array($shop->id, $addedIds, true)) {
                    $all->push($shop);
                    $addedIds[] = $shop->id;
                }
            }
            foreach ($partials as $shop) {
                if (!in_array($shop->id, $addedIds, true)) {
                    $all->push($shop);
                    $addedIds[] = $shop->id;
                }
            }

            Log::debug('重複排除後の全件', $all->toArray());
            $result = $all->take(5)->map(function ($shop) use ($query) {
                return [
                    'id' => $shop->id,
                    'name' => $shop->name,
                    'address' => $shop->address,
                    'formatted_phone_number' => $shop->formatted_phone_number,
                    'website' => $shop->website,
                    'google_place_id' => $shop->google_place_id,
                    'latitude' => $shop->latitude,
                    'longitude' => $shop->longitude,
                    'is_existing' => true,
                    'source' => 'database',
                    'match_score' => $this->calculateMatchScore($shop->name, $query),
                ];
            })->toArray();
            Log::debug('フォールバック検索return直前', $result);
            return $result;
        } catch (\Exception $e) {
            Log::error('フォールバック検索例外', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 検索結果のマッチスコアを計算
     */
    private function calculateMatchScore(string $shopName, string $query): int
    {
        $shopNameLower = mb_strtolower($shopName);
        $queryLower = mb_strtolower($query);

        // 完全一致: 100点
        if ($shopNameLower === $queryLower) {
            return 100;
        }

        // 前方一致: 80点
        if (mb_strpos($shopNameLower, $queryLower) === 0) {
            return 80;
        }

        // 部分一致: 60点
        if (mb_strpos($shopNameLower, $queryLower) !== false) {
            return 60;
        }

        // 正規化後の一致: 40点
        $normalizedShopName = $this->textNormalizationService->normalizeShopName($shopName);
        $normalizedQuery = $this->textNormalizationService->normalizeShopName($query);

        if (mb_strpos(mb_strtolower($normalizedShopName), mb_strtolower($normalizedQuery)) !== false) {
            return 40;
        }

        return 0;
    }

    /**
     * 検索結果を統合・重複除去（最適化版）
     */
    private function mergeResultsOptimized(array $googlePlaces, array $existingShops): array
    {
        // Google側が空なら既存DBの全件をそのまま返す
        if (empty($googlePlaces)) {
            return $existingShops;
        }

        $results = [];
        $processedPlaceIds = [];
        $processedNames = [];

        // 1. Google Places APIの結果を追加
        foreach ($googlePlaces as $place) {
            $results[] = $place;
            if ($place['google_place_id']) {
                $processedPlaceIds[] = $place['google_place_id'];
            }
            $processedNames[] = mb_strtolower($place['name']);
        }

        // 2. 既存DBの結果を追加（重複を除く）
        foreach ($existingShops as $shop) {
            $shopNameLower = mb_strtolower($shop['name']);

            // Google Place IDまたは名前で重複チェック
            if (
                !in_array($shop['google_place_id'], $processedPlaceIds) &&
                !in_array($shopNameLower, $processedNames)
            ) {
                $results[] = $shop;
                $processedPlaceIds[] = $shop['google_place_id'];
                $processedNames[] = $shopNameLower;
            }
        }

        // 3. マッチスコアでソート
        usort($results, function ($a, $b) {
            return ($b['match_score'] ?? 0) - ($a['match_score'] ?? 0);
        });

        return $results;
    }

    /**
     * 店舗詳細情報の取得（Google Places API）
     */
    public function getPlaceDetails(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'place_id' => 'required|string',
            ]);

            $placeId = $request->input('place_id');
            $details = $this->googlePlacesService->getPlaceDetails($placeId);

            return response()->json([
                'success' => true,
                'data' => $details
            ]);
        } catch (Exception $e) {
            Log::error('Place details error', [
                'place_id' => $request->input('place_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => '店舗詳細の取得に失敗しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
