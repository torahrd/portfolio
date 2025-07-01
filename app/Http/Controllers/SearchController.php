<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Post;
use App\Services\ShopSearchService;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
  private ShopSearchService $shopSearchService;

  public function __construct(ShopSearchService $shopSearchService)
  {
    $this->shopSearchService = $shopSearchService;
  }

  public function index(Request $request)
  {
    $query = $request->input('q', '');
    $shop = null;
    $posts = collect();
    $hasPosts = false;

    if ($query) {
      // 統合検索サービスを使用して店舗を検索
      $searchResults = $this->shopSearchService->searchShops($query, 'ja', true);

      if (!empty($searchResults)) {
        // 最初の結果を取得
        $firstResult = $searchResults[0];

        // 既存店舗の場合はDBから詳細情報を取得
        if (isset($firstResult['is_existing']) && $firstResult['is_existing']) {
          $shop = Shop::find($firstResult['id']);
        } else {
          // 新規店舗の場合は検索結果から店舗オブジェクトを作成
          $shop = new Shop([
            'name' => $firstResult['name'],
            'address' => $firstResult['address'],
            'google_place_id' => $firstResult['google_place_id'],
            'latitude' => $firstResult['latitude'],
            'longitude' => $firstResult['longitude'],
          ]);
        }

        // 投稿があるかチェック
        if ($shop && $shop->id) {
          $posts = Post::where('shop_id', $shop->id)
            ->with('user', 'shop')
            ->withCount(['favorite_users', 'comments'])
            ->latest()
            ->get();
          $hasPosts = $posts->count() > 0;
        } else {
          // 新規店舗の場合は投稿なし
          $hasPosts = false;
        }
      }
    }

    return view('search.index', [
      'query' => $query,
      'shop' => $shop,
      'posts' => $posts,
      'hasPosts' => $hasPosts,
      'searchResults' => $searchResults ?? []
    ]);
  }
}
