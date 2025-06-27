<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;

class ShopMapApiController extends Controller
{
  public function index(Request $request)
  {
    // 投稿が1件以上あり、かつ緯度・経度が設定されている店舗のみ取得
    $shops = Shop::with(['posts' => function ($q) {
      $q->latest();
    }])->whereHas('posts')
      ->whereNotNull('latitude')
      ->whereNotNull('longitude')
      ->with('business_hours')
      ->get();

    $data = $shops->map(function ($shop) {
      $latestPost = $shop->posts->first();
      $isOpen = $shop->is_open_now ?? false;
      return [
        'id' => $shop->id,
        'name' => $shop->name,
        'lat' => $shop->latitude,
        'lng' => $shop->longitude,
        'image_url' => $latestPost?->image_url ?? null,
        'is_open' => $isOpen,
        'shop_url' => route('shops.show', $shop),
      ];
    });
    return response()->json($data);
  }
}
