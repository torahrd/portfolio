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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ===== 基本ルート =====

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== 投稿機能のルート =====

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
    // 店舗検索（GET）- レート制限付き
    Route::get('/shops/search', [ShopController::class, 'search'])
        ->name('shops.search')
        ->middleware('throttle:60,1');

    // 店舗作成（POST）
    Route::post('/shops', [ShopController::class, 'store'])->name('shops.store');

    // 店舗詳細（GET）
    Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');
});

// ===== お気に入り機能のルート =====

Route::middleware(['auth'])->group(function () {
    // お気に入り追加（POST）
    Route::post('/shops/{shop}/favorite', [FavoriteShopController::class, 'store'])
        ->name('shops.favorite.store');

    // お気に入り削除（DELETE）
    Route::delete('/shops/{shop}/favorite', [FavoriteShopController::class, 'destroy'])
        ->name('shops.favorite.destroy');

    // お気に入り状態取得（GET）
    Route::get('/shops/{shop}/favorite/status', [FavoriteShopController::class, 'status'])
        ->name('shops.favorite.status');
});

// ===== メンション機能用のユーザー検索ルート =====

Route::middleware(['auth'])->group(function () {
    Route::get('/users/search', [UserSearchController::class, 'search'])
        ->name('users.search')
        ->middleware('throttle:60,1');
});

// ===== 新規追加：プロフィール・フォロー機能のルート =====

// 認証が必要なプロフィール関連ルート
Route::middleware('auth')->group(function () {
    // プロフィール編集
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/generate-link', [ProfileController::class, 'generateProfileLink'])->name('profile.generate-link');

    // フォロー関連
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::post('/users/{user}/accept', [FollowController::class, 'acceptFollowRequest'])->name('users.accept');
    Route::post('/users/{user}/reject', [FollowController::class, 'rejectFollowRequest'])->name('users.reject');
});

// パブリックルート（認証不要）
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/users/{user}/followers', [FollowController::class, 'followers'])->name('profile.followers');
Route::get('/users/{user}/following', [FollowController::class, 'following'])->name('profile.following');
Route::get('/profile-link/{token}', [ProfileController::class, 'showByToken'])->name('profile.show-by-token');

// ===== 既存のプロフィール機能のルート（Laravel Breezeデフォルト） =====

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== 認証関連ルート =====

require __DIR__ . '/auth.php';
