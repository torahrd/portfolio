<?php

use App\Http\Controllers\Api\FollowRequestController;
use App\Http\Controllers\Api\GooglePlacesProxyController;
use App\Http\Controllers\Api\SearchSuggestionController;
use App\Http\Controllers\Api\ShopMapApiController;
use App\Http\Controllers\Api\ShopSearchController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// フォロー申請関連のAPI（認証必須）
Route::middleware('auth:sanctum')->group(function () {
    // フォロー申請一覧取得
    Route::get('/follow-requests', [FollowRequestController::class, 'index'])
        ->name('api.follow-requests.index');

    // フォロー申請数取得
    Route::get('/follow-requests/count', [FollowRequestController::class, 'count'])
        ->name('api.follow-requests.count');

    // フォロー申請の一括処理
    Route::post('/follow-requests/mark-read', [FollowRequestController::class, 'markAsRead'])
        ->name('api.follow-requests.mark-read');
});

// Google Places API連携の店舗検索（認証必須）
Route::middleware(['auth:sanctum'])->group(function () {
    // 店舗検索（Google Places API + 既存データベース）
    Route::get('/shops/search-places', [ShopSearchController::class, 'search'])
        ->name('api.shops.search-places');

    // 店舗詳細情報取得（Google Places API）
    Route::get('/shops/place-details', [ShopSearchController::class, 'getPlaceDetails'])
        ->name('api.shops.place-details');
});

// Google Places API プロキシ（認証必須）
Route::middleware(['auth:sanctum'])->group(function () {
    // テキスト検索プロキシ
    Route::post('/places/search-text', [GooglePlacesProxyController::class, 'searchText'])
        ->name('api.places.search-text');

    // 場所詳細取得プロキシ
    Route::post('/places/details', [GooglePlacesProxyController::class, 'getPlaceDetails'])
        ->name('api.places.details');
});

// 認証が必要なAPI
Route::middleware(['auth:sanctum'])->group(function () {
    // 最近の店舗取得（投稿作成画面用）
    Route::get('/shops/recent', [ShopController::class, 'recent'])->name('api.shops.recent');
});

// 認証が必要な店舗関連API（認証方式統一）
Route::middleware(['auth:sanctum'])->group(function () {
    // 店舗検索（投稿作成画面用）
    Route::get('/shops/search', [ShopController::class, 'search'])->name('api.shops.search');

    // 新規店舗作成
    Route::post('/shops', [ShopController::class, 'store'])->name('api.shops.store');

    // 店舗選択検証API
    Route::post('/shops/validate-selection', [\App\Http\Controllers\Api\ShopSearchController::class, 'validateSelection'])->name('api.shops.validate-selection');
});

// 地図データ取得
Route::get('/shops/map-data', [ShopMapApiController::class, 'index'])->name('api.shops.map-data');

// 店舗名サジェストAPI
Route::get('/search/suggestions', [SearchSuggestionController::class, 'shopNameSuggestions'])->name('api.search.suggestions');

// デモ用API（認証不要）
Route::prefix('demo')->group(function () {
    // デモ用店舗検索
    Route::post('/shops/search', function (Request $request) {
        $query = $request->input('query', '');

        // モックデータを返す
        $mockShops = [
            ['id' => 101, 'name' => 'タニヤ タイ料理', 'address' => '東京都渋谷区道玄坂2-10-7', 'lat' => 35.6580, 'lng' => 139.6982],
            ['id' => 102, 'name' => 'すし田中', 'address' => '東京都港区六本木6-8-29', 'lat' => 35.6627, 'lng' => 139.7313],
            ['id' => 103, 'name' => 'フレンチ・ラパン', 'address' => '東京都千代田区丸の内1-9-1', 'lat' => 35.6812, 'lng' => 139.7671],
            ['id' => 104, 'name' => 'イタリアーノ・ベッロ', 'address' => '東京都中央区銀座3-4-12', 'lat' => 35.6719, 'lng' => 139.7661],
            ['id' => 105, 'name' => '鮨 青木', 'address' => '東京都中央区銀座7-5-4', 'lat' => 35.6708, 'lng' => 139.7634],
            ['id' => 106, 'name' => 'ビストロ・パリジャン', 'address' => '東京都渋谷区恵比寿1-8-14', 'lat' => 35.6468, 'lng' => 139.7101],
            ['id' => 107, 'name' => '焼肉 叙々苑', 'address' => '東京都新宿区新宿3-17-4', 'lat' => 35.6938, 'lng' => 139.7036],
            ['id' => 108, 'name' => 'カフェ・ド・フロール', 'address' => '東京都港区表参道4-9-3', 'lat' => 35.6658, 'lng' => 139.7128],
        ];

        if (empty($query)) {
            return response()->json($mockShops);
        }

        $filtered = array_filter($mockShops, function ($shop) use ($query) {
            return stripos($shop['name'], $query) !== false ||
                   stripos($shop['address'], $query) !== false;
        });

        return response()->json(array_values($filtered));
    })->name('api.demo.shops.search');

    // デモ用店舗詳細
    Route::post('/shops/{id}/details', function (Request $request, $id) {
        return response()->json([
            'success' => true,
            'shop' => [
                'id' => $id,
                'name' => 'デモ店舗',
                'address' => '東京都渋谷区',
                'phone' => '03-1234-5678',
                'website' => 'https://example.com',
            ],
        ]);
    })->name('api.demo.shops.details');
});
