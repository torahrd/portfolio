<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\Shop;
use Illuminate\Support\Facades\Log;

class ShopSearchService
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
     * Google Places APIから常に5件取得し、DBは投稿数付与・既存判定のみに使う統合検索
     *
     * @param  string  $query  検索クエリ
     * @param  string  $language  言語コード
     * @param  bool  $includePostCount  投稿数を含めるか
     * @return array 検索結果
     */
    public function searchShops(string $query, string $language = 'ja', bool $includePostCount = false): array
    {
        Log::debug('統合検索開始', [
            'query' => $query,
            'language' => $language,
            'include_post_count' => $includePostCount,
        ]);

        try {
            // 1. Google Places APIから常に5件取得
            $googleResults = $this->searchGooglePlaces($query, $language);
            $googleResults = array_slice($googleResults, 0, 5);

            // 2. DBから既存店舗情報・投稿数を取得
            $googleResultsWithDb = $this->mergeDbInfoToGoogleResults($googleResults);

            // 3. 投稿数を付与（必要に応じて）
            if ($includePostCount) {
                $googleResultsWithDb = $this->addPostCounts($googleResultsWithDb);
            }

            Log::debug('Google優先検索完了', [
                'query' => $query,
                'google_count' => count($googleResults),
                'final_count' => count($googleResultsWithDb),
            ]);

            return $googleResultsWithDb;
        } catch (\Exception $e) {
            Log::error('Google優先検索エラー', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            // エラー時は既存DB検索のみでフォールバック
            $fallbackResults = $this->searchExistingShops($query);
            if ($includePostCount) {
                $fallbackResults = $this->addPostCounts($fallbackResults);
            }

            return array_slice($fallbackResults, 0, 5);
        }
    }

    /**
     * Google Places APIから店舗を検索
     */
    private function searchGooglePlaces(string $query, string $language): array
    {
        try {
            $places = $this->googlePlacesService->searchPlaceNew($query, $language);

            return $this->googlePlacesService->transformPlacesToShops($places, $query);
        } catch (\Exception $e) {
            Log::warning('Google Places API検索失敗', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * DB内の曖昧検索を強化
     * - 正規化パターン（ひらがな・カタカナ・ローマ字・英語）
     * - 部分一致・前方一致
     * - 代表的な略称（エイリアス辞書）
     */
    private function searchExistingShops(string $query): array
    {
        // 1. 正規化パターン生成
        $patterns = [$query];
        $normalized = $this->textNormalizationService->normalizeShopName($query);
        if ($normalized !== $query) {
            $patterns[] = $normalized;
        }

        // 2. 代表的な略称・エイリアス辞書（例：スタバ→スターバックス、starbucks）
        $aliasMap = [
            'スタバ' => ['スターバックス', 'starbucks'],
            'すたば' => ['スターバックス', 'starbucks'],
            'sutaba' => ['スターバックス', 'starbucks'],
            'マック' => ['マクドナルド', 'mcdonalds'],
            'マクド' => ['マクドナルド', 'mcdonalds'],
            // 必要に応じて追加
        ];
        foreach ($aliasMap as $alias => $targets) {
            if (mb_stripos($query, $alias) !== false) {
                foreach ($targets as $target) {
                    $patterns[] = $target;
                }
            }
        }

        // 3. パターンごとに完全一致・前方一致・部分一致でDB検索
        $all = collect();
        $addedIds = [];
        foreach ($patterns as $pattern) {
            // 完全一致
            $exact = Shop::where('name', $pattern)
                ->select(['id', 'name', 'address', 'formatted_phone_number', 'website', 'google_place_id', 'latitude', 'longitude'])
                ->get();
            foreach ($exact as $shop) {
                if (! in_array($shop->id, $addedIds, true)) {
                    $all->push($shop);
                    $addedIds[] = $shop->id;
                }
            }
            // 前方一致
            $starts = Shop::where('name', 'LIKE', "$pattern%")
                ->select(['id', 'name', 'address', 'formatted_phone_number', 'website', 'google_place_id', 'latitude', 'longitude'])
                ->get();
            foreach ($starts as $shop) {
                if (! in_array($shop->id, $addedIds, true)) {
                    $all->push($shop);
                    $addedIds[] = $shop->id;
                }
            }
            // 部分一致
            $partial = Shop::where('name', 'LIKE', "%$pattern%")
                ->select(['id', 'name', 'address', 'formatted_phone_number', 'website', 'google_place_id', 'latitude', 'longitude'])
                ->get();
            foreach ($partial as $shop) {
                if (! in_array($shop->id, $addedIds, true)) {
                    $all->push($shop);
                    $addedIds[] = $shop->id;
                }
            }
        }

        // 4. 結果を5件まで返却
        return $all->take(5)->map(function ($shop) use ($query) {
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
    }

    /**
     * Google Places APIの候補にDBの既存店舗情報・投稿数をマージ
     */
    private function mergeDbInfoToGoogleResults(array $googleResults): array
    {
        // Google Place IDでDBの既存店舗を検索
        $placeIds = array_filter(array_map(fn ($g) => $g['google_place_id'] ?? null, $googleResults));
        $dbShops = Shop::whereIn('google_place_id', $placeIds)->get()->keyBy('google_place_id');

        return array_map(function ($g) use ($dbShops) {
            $dbShop = $dbShops[$g['google_place_id']] ?? null;
            if ($dbShop) {
                // 既存店舗の場合はDB情報を優先
                return array_merge($g, [
                    'id' => $dbShop->id,
                    'is_existing' => true,
                    'source' => 'database',
                ]);
            } else {
                // 新規店舗
                return array_merge($g, [
                    'is_existing' => false,
                    'source' => 'google',
                ]);
            }
        }, $googleResults);
    }

    /**
     * 投稿数を追加
     */
    private function addPostCounts(array $results): array
    {
        $shopIds = collect($results)
            ->filter(fn ($result) => isset($result['id']))
            ->pluck('id')
            ->toArray();

        if (empty($shopIds)) {
            return $results;
        }

        $postCounts = Post::whereIn('shop_id', $shopIds)
            ->selectRaw('shop_id, COUNT(*) as post_count')
            ->groupBy('shop_id')
            ->pluck('post_count', 'shop_id')
            ->toArray();

        return array_map(function ($result) use ($postCounts) {
            $result['post_count'] = $postCounts[$result['id']] ?? 0;

            return $result;
        }, $results);
    }

    /**
     * マッチスコアを計算
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
     * 店舗選択必須化のための検証
     */
    public function validateShopSelection(array $selectedShop): bool
    {
        // 必須項目のチェック
        $requiredFields = ['name', 'address'];
        foreach ($requiredFields as $field) {
            if (empty($selectedShop[$field])) {
                return false;
            }
        }

        // 既存店舗の場合はIDが必要
        if (isset($selectedShop['is_existing']) && $selectedShop['is_existing']) {
            if (empty($selectedShop['id'])) {
                return false;
            }
        }

        // 新規店舗の場合はGoogle Place IDが必要
        if (! isset($selectedShop['is_existing']) || ! $selectedShop['is_existing']) {
            if (empty($selectedShop['google_place_id'])) {
                return false;
            }
        }

        return true;
    }
}
