<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\FavoriteShopController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== 基本ルート =====

// ホームルート（認証必要 - 元の設計通り）
Route::get('/', [PostController::class, 'index'])->middleware(['auth'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== コンポーネントで使用される基本ページ =====

// 検索ページ
Route::get('/search', [SearchController::class, 'index'])->name('search');

// 通知ページ（認証必要）
Route::get('/notifications', function () {
    return view('notifications.index', ['notifications' => auth()->user()->notifications ?? []]);
})->middleware(['auth'])->name('notifications.index');

// 設定ページ（認証必要）
Route::get('/settings', function () {
    return view('settings.index');
})->middleware(['auth'])->name('settings');

// ===== 投稿機能のルート（元の認証設定を完全に保持）=====

Route::controller(PostController::class)->middleware(['auth'])->group(function () {
    Route::get('/posts', 'index')->name('posts.index');
    Route::get('/posts/create', 'create')->name('posts.create');
    Route::post('/posts', 'store')->name('posts.store');
    Route::get('/posts/{post}', 'show')->name('posts.show');
    Route::put('/posts/{post}', 'update')->name('posts.update');
    Route::get('/posts/{post}/edit', 'edit')->name('posts.edit');
    Route::delete('/posts/{post}', 'destroy')->name('posts.destroy');
});

// ===== ユーザー機能のルート =====

Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    Route::get('/users', 'index')->name('user');
});

// ===== コメント機能のルート =====

Route::controller(CommentController::class)->middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/comments', 'store')->name('comments.store');
    Route::delete('/comments/{comment}', 'destroy')->name('comments.destroy');
});

// ===== 店舗機能のルート =====

Route::middleware(['auth'])->group(function () {
    // 最近の店舗取得（投稿作成画面用）
    Route::get('/shops/recent', [ShopController::class, 'recent'])->name('shops.recent');
    // 既存の店舗検索ルートを確認・追加
    Route::get('/shops/search', [ShopController::class, 'search'])
        ->name('shops.search')
        ->middleware('throttle:60,1');
    // 新規店舗作成（既存のルートを確認）
    Route::post('/shops', [ShopController::class, 'store'])->name('shops.store');
    // 店舗詳細（既存）
    Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');
});

// ===== お気に入り機能のルート =====

Route::middleware(['auth'])->group(function () {
    Route::post('/shops/{shop}/favorite', [FavoriteShopController::class, 'store'])
        ->name('shops.favorite.store');
    Route::delete('/shops/{shop}/favorite', [FavoriteShopController::class, 'destroy'])
        ->name('shops.favorite.destroy');
    Route::get('/shops/{shop}/favorite/status', [FavoriteShopController::class, 'status'])
        ->name('shops.favorite.status');
});

// ===== メンション機能用のユーザー検索ルート =====

Route::middleware(['auth'])->group(function () {
    Route::get('/users/search', [UserSearchController::class, 'search'])
        ->name('users.search')
        ->middleware('throttle:60,1');
});

// ===== プロフィール・フォロー機能のルート =====

// パブリックルート（認証不要）
Route::get('/users/{user}', [ProfileController::class, 'show'])
    ->name('profile.show');
Route::get('/users/{user}/followers', [FollowController::class, 'followers'])
    ->name('profile.followers');
Route::get('/users/{user}/following', [FollowController::class, 'following'])
    ->name('profile.following');
Route::get('/profile-link/{token}', [ProfileController::class, 'showByToken'])
    ->name('profile.show-by-token');

// 認証が必要なプロフィール関連ルート
Route::middleware('auth')->group(function () {
    // プロフィール編集（Laravel Breezeとの統合）
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // プロフィールリンク生成
    Route::post('/profile/generate-link', [ProfileController::class, 'generateProfileLink'])
        ->name('profile.generate-link');

    // フォロー関連（AJAX対応、レート制限付き）
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])
        ->name('users.follow')
        ->middleware('throttle:30,1');
    Route::post('/users/{user}/accept', [FollowController::class, 'acceptFollowRequest'])
        ->name('users.accept')
        ->middleware('throttle:30,1');
    Route::post('/users/{user}/reject', [FollowController::class, 'rejectFollowRequest'])
        ->name('users.reject')
        ->middleware('throttle:30,1');
});

// ===== 認証関連ルート =====

require __DIR__ . '/auth.php';

Route::get('/map', [MapController::class, 'index'])->name('map.index');
