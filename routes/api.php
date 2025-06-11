<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FollowRequestController;

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
