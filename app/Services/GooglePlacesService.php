<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;

class GooglePlacesService
{
  private GooglePlaces $client;
  private string $cachePrefix = 'google_places:';

  // レート制限設定
  private int $dailyLimit = 300;
  private int $monthlyLimit = 9000;

  public function __construct()
  {
    $this->client = GooglePlaces::make(key: config('services.google.places_api_key'));
  }

  /**
   * 店舗名で検索
   */
  public function searchPlace(string $query, string $language = 'ja'): array
  {
    try {
      // レート制限チェック
      if ($this->isLimitExceeded()) {
        throw new Exception('API使用量上限に達しました。しばらく時間をおいてから再試行してください。');
      }

      // キャッシュキー
      $cacheKey = $this->cachePrefix . 'search:' . md5($query . $language);

      // キャッシュから取得を試行
      $result = Cache::remember($cacheKey, 1800, function () use ($query, $language) {
        // Google Places API呼び出し
        $response = $this->client->textSearch($query, [
          'language' => $language,
          'types' => 'establishment',
          'region' => 'JP'
        ]);

        if (!$response->successful()) {
          throw new Exception('Google Places API呼び出しに失敗しました: ' . $response->status());
        }

        return $response->json()['results'] ?? [];
      });

      // 使用量記録
      $this->recordUsage();

      return $result;
    } catch (Exception $e) {
      Log::error('Google Places API search error', [
        'query' => $query,
        'error' => $e->getMessage(),
        'daily_count' => $this->getDailyCount(),
        'monthly_count' => $this->getMonthlyCount()
      ]);

      throw $e;
    }
  }

  /**
   * Place IDから詳細情報を取得
   */
  public function getPlaceDetails(string $placeId, array $fields = []): array
  {
    try {
      // レート制限チェック
      if ($this->isLimitExceeded()) {
        throw new Exception('API使用量上限に達しました。しばらく時間をおいてから再試行してください。');
      }

      // デフォルトフィールド（Google Places API (New)仕様に修正）
      $defaultFields = [
        'id',
        'displayName',
        'shortFormattedAddress',
        'nationalPhoneNumber',
        'websiteUri',
        'regularOpeningHours',
        'location',
      ];

      $requestedFields = $fields ?: $defaultFields;

      // キャッシュキー
      $cacheKey = $this->cachePrefix . 'details:' . $placeId;

      // キャッシュから取得を試行（24時間キャッシュ）
      $result = Cache::remember($cacheKey, 86400, function () use ($placeId, $requestedFields) {
        // 新API用のplaceDetails呼び出し
        $response = $this->client->placeDetails($placeId, $requestedFields);
        Log::info('Google Places API response', [
          'place_id' => $placeId,
          'response' => $response->json()
        ]);
        if (!$response->successful()) {
          throw new Exception('Place details API呼び出しに失敗しました: ' . $response->status());
        }
        return $response->json()['result'] ?? [];
      });

      // 使用量記録
      $this->recordUsage();

      return $result;
    } catch (Exception $e) {
      Log::error('Google Places API details error', [
        'place_id' => $placeId,
        'error' => $e->getMessage(),
        'daily_count' => $this->getDailyCount(),
        'monthly_count' => $this->getMonthlyCount()
      ]);

      throw $e;
    }
  }

  /**
   * 住所から座標を取得（Geocoding）
   */
  public function geocodeAddress(string $address): ?array
  {
    try {
      // レート制限チェック
      if ($this->isLimitExceeded()) {
        throw new Exception('API使用量上限に達しました。しばらく時間をおいてから再試行してください。');
      }

      // キャッシュキー
      $cacheKey = $this->cachePrefix . 'geocode:' . md5($address);

      // キャッシュから取得を試行（1週間キャッシュ）
      $result = Cache::remember($cacheKey, 604800, function () use ($address) {
        // 新しいAPIではGeocodingは別途実装が必要
        // 一時的にPlace Searchで代替
        $response = $this->client->textSearch($address, [
          'language' => 'ja',
          'region' => 'JP'
        ]);

        if (!$response->successful()) {
          throw new Exception('Geocoding API呼び出しに失敗しました: ' . $response->status());
        }

        $results = $response->json()['results'] ?? [];
        return $results[0] ?? null;
      });

      // 使用量記録
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
