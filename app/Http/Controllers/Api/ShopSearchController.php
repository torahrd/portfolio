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
     * 店舗名で検索（Google Places API + 既存データベース）
     */
    public function search(Request $request): JsonResponse
    {
        try {
            // バリデーション
            $request->validate([
                'query' => 'required|string|min:1|max:100',
                'language' => 'string|in:ja,en',
            ]);

            $query = $request->input('query');
            $language = $request->input('language', 'ja');

            // 1. 既存データベースから検索
            $existingShops = $this->searchExistingShops($query);

            // 2. Google Places APIから検索
            $googlePlaces = $this->searchGooglePlaces($query, $language);

            // 3. 結果を統合・重複除去
            $results = $this->mergeResults($existingShops, $googlePlaces);

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

            return response()->json([
                'success' => false,
                'message' => '検索中にエラーが発生しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 既存データベースから店舗を検索
     */
    private function searchExistingShops(string $query): array
    {
        // 正規化されたクエリで検索
        $normalizedQuery = $this->textNormalizationService->normalizeShopName($query);

        $shops = Shop::where(function ($q) use ($query, $normalizedQuery) {
            $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('name', 'LIKE', "%{$normalizedQuery}%");
        })
            ->with(['business_hours', 'creator:id,name'])
            ->limit(5)
            ->get();

        return $shops->map(function ($shop) {
            return [
                'id' => $shop->id,
                'name' => $shop->name,
                'address' => $shop->address,
                'phone' => $shop->phone,
                'website' => $shop->website,
                'google_place_id' => $shop->google_place_id,
                'latitude' => $shop->latitude,
                'longitude' => $shop->longitude,
                'is_existing' => true,
                'source' => 'database',
                'favorites_count' => $shop->favorites_count,
                'recent_posts_count' => $shop->posts()->count(),
            ];
        })->toArray();
    }

    /**
     * Google Places APIから店舗を検索
     */
    private function searchGooglePlaces(string $query, string $language): array
    {
        try {
            $places = $this->googlePlacesService->searchPlace($query, $language);

            return collect($places)->map(function ($place) {
                // 既存の店舗かチェック
                $existingShop = Shop::findByGooglePlaceId($place['place_id'] ?? '');

                return [
                    'id' => $existingShop?->id,
                    'name' => $place['name'] ?? '',
                    'address' => $place['formatted_address'] ?? '',
                    'phone' => $place['formatted_phone_number'] ?? '',
                    'website' => $place['website'] ?? '',
                    'google_place_id' => $place['place_id'] ?? '',
                    'latitude' => $place['geometry']['location']['lat'] ?? null,
                    'longitude' => $place['geometry']['location']['lng'] ?? null,
                    'is_existing' => $existingShop !== null,
                    'source' => 'google_places',
                    'rating' => $place['rating'] ?? null,
                    'user_ratings_total' => $place['user_ratings_total'] ?? null,
                    'opening_hours' => $place['opening_hours'] ?? null,
                ];
            })->toArray();
        } catch (Exception $e) {
            Log::warning('Google Places API search failed', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * 検索結果を統合・重複除去
     */
    private function mergeResults(array $existingShops, array $googlePlaces): array
    {
        $results = [];
        $processedPlaceIds = [];

        // 1. 既存店舗を優先的に追加
        foreach ($existingShops as $shop) {
            $results[] = $shop;
            if ($shop['google_place_id']) {
                $processedPlaceIds[] = $shop['google_place_id'];
            }
        }

        // 2. Google Places APIの結果を追加（重複を除く）
        foreach ($googlePlaces as $place) {
            if (!in_array($place['google_place_id'], $processedPlaceIds)) {
                $results[] = $place;
                $processedPlaceIds[] = $place['google_place_id'];
            }
        }

        // 3. 結果を制限（最大10件）
        return array_slice($results, 0, 10);
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
