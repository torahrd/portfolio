<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GooglePlacesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GooglePlacesProxyController extends Controller
{
    private GooglePlacesService $placesService;

    public function __construct(GooglePlacesService $placesService)
    {
        $this->placesService = $placesService;
    }

    /**
     * テキスト検索のプロキシエンドポイント
     */
    public function searchText(Request $request): JsonResponse
    {
        try {
            // 入力バリデーション
            $request->validate([
                'query' => 'required|string|max:255',
                'language' => 'string|max:10',
            ]);

            $query = $request->input('query');
            $language = $request->input('language', 'ja');

            // レート制限チェック（ユーザーごと）
            $userKey = 'places_search:'.($request->user()?->id ?? 'guest');
            if (! $this->checkRateLimit($userKey, 10, 60)) { // 1分間に10回
                return response()->json([
                    'error' => 'レート制限に達しました。しばらく時間をおいてから再試行してください。',
                ], 429);
            }

            // キャッシュチェック
            $cacheKey = 'places_search:'.md5($query.$language);
            $cachedResult = Cache::get($cacheKey);
            if ($cachedResult) {
                Log::info('Google Places API プロキシ: キャッシュヒット', [
                    'query' => $query,
                    'user_id' => $request->user()?->id,
                ]);

                return response()->json($cachedResult);
            }

            // Google Places API呼び出し
            Log::info('Google Places API プロキシ: 検索実行', [
                'query' => $query,
                'language' => $language,
                'user_id' => $request->user()?->id,
            ]);

            $result = $this->placesService->searchPlaceNew($query, $language);

            // フロントエンド用の形式に変換
            $transformedResult = $this->placesService->transformPlacesToShops($result, $query);

            // 結果をキャッシュ（30分）
            Cache::put($cacheKey, $transformedResult, 1800);

            return response()->json($transformedResult);

        } catch (Exception $e) {
            Log::error('Google Places API プロキシ: 検索エラー', [
                'query' => $request->input('query'),
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'error' => '検索中にエラーが発生しました。しばらく時間をおいてから再試行してください。',
            ], 500);
        }
    }

    /**
     * 場所詳細取得のプロキシエンドポイント
     */
    public function getPlaceDetails(Request $request): JsonResponse
    {
        try {
            // 入力バリデーション
            $request->validate([
                'place_id' => 'required|string|max:255',
                'language' => 'string|max:10',
            ]);

            $placeId = $request->input('place_id');
            $language = $request->input('language', 'ja');

            // レート制限チェック（ユーザーごと）
            $userKey = 'places_details:'.($request->user()?->id ?? 'guest');
            if (! $this->checkRateLimit($userKey, 20, 60)) { // 1分間に20回
                return response()->json([
                    'error' => 'レート制限に達しました。しばらく時間をおいてから再試行してください。',
                ], 429);
            }

            // キャッシュチェック
            $cacheKey = 'places_details:'.md5($placeId.$language);
            $cachedResult = Cache::get($cacheKey);
            if ($cachedResult) {
                Log::info('Google Places API プロキシ: 詳細キャッシュヒット', [
                    'place_id' => $placeId,
                    'user_id' => $request->user()?->id,
                ]);

                return response()->json($cachedResult);
            }

            // Google Places API呼び出し
            Log::info('Google Places API プロキシ: 詳細取得実行', [
                'place_id' => $placeId,
                'language' => $language,
                'user_id' => $request->user()?->id,
            ]);

            $result = $this->placesService->getPlaceDetailsNew($placeId, $language);

            if ($result) {
                // 結果をキャッシュ（1時間）
                Cache::put($cacheKey, $result, 3600);

                return response()->json($result);
            } else {
                return response()->json([
                    'error' => '指定された場所が見つかりませんでした。',
                ], 404);
            }

        } catch (Exception $e) {
            Log::error('Google Places API プロキシ: 詳細取得エラー', [
                'place_id' => $request->input('place_id'),
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'error' => '詳細取得中にエラーが発生しました。しばらく時間をおいてから再試行してください。',
            ], 500);
        }
    }

    /**
     * レート制限チェック
     *
     * @param  string  $key  レート制限キー
     * @param  int  $maxAttempts  最大試行回数
     * @param  int  $decayMinutes  制限時間（分）
     */
    private function checkRateLimit(string $key, int $maxAttempts, int $decayMinutes): bool
    {
        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return false;
        }

        Cache::put($key, $attempts + 1, $decayMinutes);

        return true;
    }
}
