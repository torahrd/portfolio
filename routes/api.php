<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FollowRequestController;
use App\Http\Controllers\Api\ShopSearchController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Api\ShopMapApiController;
use App\Http\Controllers\Api\SearchSuggestionController;
use App\Http\Controllers\Api\GooglePlacesProxyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// フォロー申請関連のAPI（認証必須）
Route::middleware('auth')->group(function () {
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

// 認証が必要な店舗関連API（web.phpにも追加）
Route::middleware(['auth'])->group(function () {
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
