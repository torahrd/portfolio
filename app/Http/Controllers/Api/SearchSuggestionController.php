<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShopSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SearchSuggestionController extends Controller
{
  private ShopSearchService $shopSearchService;

  public function __construct(ShopSearchService $shopSearchService)
  {
    $this->shopSearchService = $shopSearchService;
  }

  /**
   * 店舗名サジェストAPI（投稿数付き）
   */
  public function shopNameSuggestions(Request $request): JsonResponse
  {
    try {
      $query = $request->input('q', '');
      if (mb_strlen($query) < 2) {
        return response()->json(['suggestions' => []]);
      }

      // 統合検索サービスを使用（投稿数付き）
      $results = $this->shopSearchService->searchShops($query, 'ja', true);

      $suggestions = collect($results)->map(function ($shop) {
        return [
          'title' => $shop['name'],
          'subtitle' => $shop['address'],
          'post_count' => $shop['post_count'] ?? 0,
          'url' => route('search', ['q' => $shop['name']]),
          'category' => '店舗',
          'shop_data' => $shop
        ];
      });

      return response()->json(['suggestions' => $suggestions]);
    } catch (\Exception $e) {
      Log::error('Shop suggestions error', [
        'query' => $request->input('q'),
        'error' => $e->getMessage()
      ]);

      return response()->json([
        'suggestions' => [],
        'error' => '検索候補の取得に失敗しました。'
      ]);
    }
  }
}
