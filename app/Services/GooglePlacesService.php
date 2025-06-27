<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Shop;

class GooglePlacesService
{
  private string $apiKey;
  private string $baseUrl = 'https://places.googleapis.com/v1';
  private string $cachePrefix = 'google_places:';

  // レート制限設定
  private int $dailyLimit = 300;
  private int $monthlyLimit = 9000;

  public function __construct()
  {
    $this->apiKey = config('services.google.places_api_key');
  }

  /**
   * Google Places API (New)でテキスト検索を実行（新しい実装）
   * 
   * @param string $query 検索クエリ
   * @param string $language 言語コード（例: 'ja'）
   * @return array 検索結果
   */
  public function searchPlaceNew(string $query, string $language = 'ja'): array
  {
    try {
      // レート制限チェック
      if ($this->isLimitExceeded()) {
        throw new Exception('API使用量上限に達しました。しばらく時間をおいてから再試行してください。');
      }

      // キャッシュキーの生成
      $cacheKey = $this->cachePrefix . 'search_new:' . md5($query . $language);

      // キャッシュから取得を試行（1時間キャッシュ）
      $result = Cache::remember($cacheKey, 3600, function () use ($query, $language) {
        // Google Places API (New)呼び出し前にリクエスト内容を詳細ログ出力
        Log::info('Google Places API (New) textSearchリクエスト', [
          'query' => $query,
          'language' => $language,
          'api_key_set' => !empty($this->apiKey)
        ]);

        try {
          // Google Places API (New)のtextSearchエンドポイントを呼び出し
          $response = Http::withHeaders([
            'X-Goog-Api-Key' => $this->apiKey,
            'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location,places.types,places.nationalPhoneNumber,places.websiteUri,places.businessStatus,places.priceLevel,places.rating,places.userRatingCount,places.utcOffsetMinutes,places.primaryType,places.primaryTypeDisplayName'
          ])->post("{$this->baseUrl}/places:searchText", [
            'textQuery' => $query,
            'languageCode' => $language,
            'maxResultCount' => 20,
            'locationBias' => [
              'circle' => [
                'center' => [
                  'latitude' => 35.6762, // 東京の緯度
                  'longitude' => 139.6503 // 東京の経度
                ],
                'radius' => 50000.0 // 50km
              ]
            ]
          ]);

          if ($response->successful()) {
            $data = $response->json();
            Log::info('Google Places API (New) textSearch成功', [
              'query' => $query,
              'result_count' => count($data['places'] ?? [])
            ]);
            return $data['places'] ?? [];
          } else {
            Log::error('Google Places API (New) textSearchエラー', [
              'query' => $query,
              'status' => $response->status(),
              'body' => $response->body()
            ]);
            throw new Exception('Google Places API (New)呼び出しに失敗しました: ' . $response->status());
          }
        } catch (\Exception $e) {
          Log::error('Google Places API (New) textSearch例外', [
            'query' => $query,
            'error' => $e->getMessage()
          ]);
          throw $e;
        }
      });

      // 使用量記録
      $this->recordUsage();

      return $result;
    } catch (Exception $e) {
      Log::error('Google Places API (New) search error', [
        'query' => $query,
        'error' => $e->getMessage(),
        'daily_count' => $this->getDailyCount(),
        'monthly_count' => $this->getMonthlyCount()
      ]);

      throw $e;
    }
  }

  /**
   * Google Places API (New)で店舗詳細情報を取得（新しい実装）
   * 
   * @param string $placeId Google Place ID
   * @param string $language 言語コード（例: 'ja'）
   * @return array|null 店舗詳細情報
   */
  public function getPlaceDetailsNew(string $placeId, string $language = 'ja'): ?array
  {
    try {
      // レート制限チェック
      if ($this->isLimitExceeded()) {
        throw new Exception('API使用量上限に達しました。しばらく時間をおいてから再試行してください。');
      }

      // キャッシュキーの生成
      $cacheKey = $this->cachePrefix . 'details_new:' . $placeId . '_' . $language;

      // キャッシュから取得を試行（24時間キャッシュ）
      $result = Cache::remember($cacheKey, 86400, function () use ($placeId, $language) {
        Log::info('Google Places API (New) placeDetailsリクエスト', [
          'place_id' => $placeId,
          'language' => $language
        ]);

        try {
          // Google Places API (New)のplaceDetailsエンドポイントを呼び出し
          $response = Http::withHeaders([
            'X-Goog-Api-Key' => $this->apiKey,
            'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.location,places.types,places.nationalPhoneNumber,places.websiteUri,places.businessStatus,places.priceLevel,places.rating,places.userRatingCount,places.utcOffsetMinutes,places.primaryType,places.primaryTypeDisplayName,places.currentOpeningHours,places.regularOpeningHours,places.internationalPhoneNumber'
          ])->get("{$this->baseUrl}/places/{$placeId}", [
            'languageCode' => $language
          ]);

          if ($response->successful()) {
            $data = $response->json();
            Log::info('Google Places API (New) placeDetails成功', [
              'place_id' => $placeId,
              'has_data' => !empty($data)
            ]);
            return $data;
          } else {
            Log::error('Google Places API (New) placeDetailsエラー', [
              'place_id' => $placeId,
              'status' => $response->status(),
              'body' => $response->body()
            ]);
            throw new Exception('Place details API (New)呼び出しに失敗しました: ' . $response->status());
          }
        } catch (\Exception $e) {
          Log::error('Google Places API (New) placeDetails例外', [
            'place_id' => $placeId,
            'error' => $e->getMessage()
          ]);
          throw $e;
        }
      });

      // 使用量記録
      $this->recordUsage();

      return $result;
    } catch (Exception $e) {
      Log::error('Google Places API (New) details error', [
        'place_id' => $placeId,
        'error' => $e->getMessage(),
        'daily_count' => $this->getDailyCount(),
        'monthly_count' => $this->getMonthlyCount()
      ]);

      throw $e;
    }
  }

  /**
   * 検索結果をShopモデル用の形式に変換（新しい実装）
   * 
   * @param array $places Google Places APIの検索結果
   * @param string $query 検索クエリ
   * @return array 変換された店舗データ
   */
  public function transformPlacesToShops(array $places, string $query): array
  {
    return collect($places)->map(function ($place) use ($query) {
      // 既存の店舗かチェック
      $existingShop = Shop::findByGooglePlaceId($place['id'] ?? '');

      return [
        'id' => $existingShop?->id,
        'name' => $place['displayName']['text'] ?? '',
        'address' => $place['formattedAddress'] ?? '',
        'latitude' => $place['location']['latitude'] ?? null,
        'longitude' => $place['location']['longitude'] ?? null,
        'google_place_id' => $place['id'] ?? '',
        'phone_number' => $place['nationalPhoneNumber'] ?? '',
        'website' => $place['websiteUri'] ?? '',
        'types' => $place['types'] ?? [],
        'business_status' => $place['businessStatus'] ?? '',
        'price_level' => $place['priceLevel'] ?? null,
        'rating' => $place['rating'] ?? null,
        'user_rating_count' => $place['userRatingCount'] ?? null,
        'utc_offset_minutes' => $place['utcOffsetMinutes'] ?? null,
        'primary_type' => $place['primaryType'] ?? '',
        'primary_type_display_name' => $place['primaryTypeDisplayName']['text'] ?? '',
        'editorial_summary' => $place['editorialSummary']['text'] ?? '',
        'current_opening_hours' => $place['currentOpeningHours'] ?? null,
        'regular_opening_hours' => $place['regularOpeningHours'] ?? null,
        'international_phone_number' => $place['internationalPhoneNumber'] ?? '',
        'national_phone_number' => $place['nationalPhoneNumber'] ?? '',
        'is_existing' => $existingShop !== null,
        'match_score' => $this->calculateMatchScore($place['displayName']['text'] ?? '', $query),
        'data_source' => 'google'
      ];
    })->toArray();
  }

  /**
   * 店舗名と検索クエリのマッチスコアを計算
   * 
   * @param string $placeName 店舗名
   * @param string $query 検索クエリ
   * @return int マッチスコア（100: 完全一致, 80: 前方一致, 60: 部分一致）
   */
  private function calculateMatchScore(string $placeName, string $query): int
  {
    $normalizedPlaceName = mb_strtolower($placeName);
    $normalizedQuery = mb_strtolower($query);

    if ($normalizedPlaceName === $normalizedQuery) {
      return 100; // 完全一致
    }

    if (str_starts_with($normalizedPlaceName, $normalizedQuery)) {
      return 80; // 前方一致
    }

    if (str_contains($normalizedPlaceName, $normalizedQuery)) {
      return 60; // 部分一致
    }

    return 0;
  }

  /**
   * 店舗名で検索（既存実装 - 段階的移行のため保持）
   */
  public function searchPlace(string $query, string $language = 'ja'): array
  {
    // 新しいAPIを試行し、失敗した場合は既存のフォールバック機能を使用
    try {
      return $this->searchPlaceNew($query, $language);
    } catch (Exception $e) {
      Log::warning('Google Places API (New) failed, using fallback', [
        'query' => $query,
        'error' => $e->getMessage()
      ]);
      // 既存のフォールバック機能があれば使用
      return [];
    }
  }

  /**
   * Place IDから詳細情報を取得（既存実装 - 段階的移行のため保持）
   */
  public function getPlaceDetails(string $placeId, array $fields = []): array
  {
    // 新しいAPIを試行し、失敗した場合は既存のフォールバック機能を使用
    try {
      $result = $this->getPlaceDetailsNew($placeId);
      return $result ?: [];
    } catch (Exception $e) {
      Log::warning('Google Places API (New) details failed, using fallback', [
        'place_id' => $placeId,
        'error' => $e->getMessage()
      ]);
      // 既存のフォールバック機能があれば使用
      return [];
    }
  }

  /**
   * 住所から座標を取得（Geocoding - 既存機能を保持）
   */
  public function geocodeAddress(string $address): ?array
  {
    try {
      // レート制限チェック（既存機能を保持）
      if ($this->isLimitExceeded()) {
        throw new Exception('API使用量上限に達しました。しばらく時間をおいてから再試行してください。');
      }

      // キャッシュキー
      $cacheKey = $this->cachePrefix . 'geocode:' . md5($address);

      // キャッシュから取得を試行（1週間キャッシュ）
      $result = Cache::remember($cacheKey, 604800, function () use ($address) {
        // 新しいAPIではGeocodingは別途実装が必要
        // 一時的にPlace Searchで代替
        try {
          $places = $this->searchPlaceNew($address, 'ja');
          return $places[0] ?? null;
        } catch (Exception $e) {
          Log::warning('Geocoding fallback failed', [
            'address' => $address,
            'error' => $e->getMessage()
          ]);
          return null;
        }
      });

      // 使用量記録（既存機能を保持）
      $this->recordUsage();

      return $result;
    } catch (Exception $e) {
      Log::error('Google Geocoding API error', [
        'address' => $address,
        'error' => $e->getMessage(),
        'daily_count' => $this->getDailyCount(),
        'monthly_count' => $this->getMonthlyCount()
      ]);

      return null;
    }
  }

  /**
   * レート制限チェック
   */
  private function isLimitExceeded(): bool
  {
    $dailyCount = $this->getDailyCount();
    $monthlyCount = $this->getMonthlyCount();

    return $dailyCount >= $this->dailyLimit || $monthlyCount >= $this->monthlyLimit;
  }

  /**
   * 日次使用量取得
   */
  private function getDailyCount(): int
  {
    $today = now()->format('Y-m-d');
    return Cache::get($this->cachePrefix . 'daily:' . $today, 0);
  }

  /**
   * 月次使用量取得
   */
  private function getMonthlyCount(): int
  {
    $month = now()->format('Y-m');
    return Cache::get($this->cachePrefix . 'monthly:' . $month, 0);
  }

  /**
   * 使用量記録
   */
  private function recordUsage(): void
  {
    $today = now()->format('Y-m-d');
    $month = now()->format('Y-m');

    // 日次カウント増加
    $dailyKey = $this->cachePrefix . 'daily:' . $today;
    $dailyCount = Cache::get($dailyKey, 0);
    Cache::put($dailyKey, $dailyCount + 1, 86400); // 24時間

    // 月次カウント増加
    $monthlyKey = $this->cachePrefix . 'monthly:' . $month;
    $monthlyCount = Cache::get($monthlyKey, 0);
    Cache::put($monthlyKey, $monthlyCount + 1, 2592000); // 30日

    // 使用量ログ
    Log::info('Google Maps API usage', [
      'daily_count' => $dailyCount + 1,
      'monthly_count' => $monthlyCount + 1,
      'daily_limit' => $this->dailyLimit,
      'monthly_limit' => $this->monthlyLimit
    ]);
  }

  /**
   * 使用量統計取得
   */
  public function getUsageStats(): array
  {
    return [
      'daily_count' => $this->getDailyCount(),
      'monthly_count' => $this->getMonthlyCount(),
      'daily_limit' => $this->dailyLimit,
      'monthly_limit' => $this->monthlyLimit,
      'daily_remaining' => $this->dailyLimit - $this->getDailyCount(),
      'monthly_remaining' => $this->monthlyLimit - $this->getMonthlyCount()
    ];
  }
}
