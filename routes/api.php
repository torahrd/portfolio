<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FollowRequestController;
use App\Http\Controllers\ShopController;

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
});
