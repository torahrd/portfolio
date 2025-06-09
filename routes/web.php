<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\FavoriteShopController;  // ★ 新規追加
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 投稿機能のルート
Route::controller(PostController::class)->middleware(['auth'])->group(function () {
    Route::get('/posts', 'index')->name('posts.index');
    Route::get('/posts/create', 'create')->name('posts.create');
    Route::post('/posts', 'store')->name('posts.store');
    Route::get('/posts/{post}', 'show')->name('posts.show');
    Route::put('/posts/{post}', 'update')->name('posts.update');
    Route::get('/posts/{post}/edit', 'edit')->name('posts.edit');
    Route::delete('/posts/{post}', 'destroy')->name('posts.destroy');
});

// ユーザー機能のルート
Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    Route::get('/users', 'index')->name('user');
});

// コメント機能のルート
Route::controller(CommentController::class)->middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/comments', 'store')->name('comments.store');
    Route::delete('/comments/{comment}', 'destroy')->name('comments.destroy');
});

// 店舗機能のルート
Route::controller(ShopController::class)->middleware(['auth'])->group(function () {
    Route::post('/shops', 'store')->name('shops.store');
    Route::get('/shops/{shop}', 'show')->name('shops.show');  // ★ 新規追加: 店舗詳細
});

// 店舗検索（レート制限付き）
Route::middleware(['throttle:60,1'])->group(function () {
    Route::controller(ShopController::class)->middleware(['auth'])->group(function () {
        Route::get('/shops/search', 'search')->name('shops.search');
    });
});

// ★ 新規追加: お気に入り機能のルート ★
Route::middleware(['auth', 'throttle:30,1'])->group(function () {
    Route::controller(FavoriteShopController::class)->group(function () {
        // お気に入り追加
        Route::post('/shops/{shop}/favorite', 'store')->name('shops.favorite.store');

        // お気に入り削除
        Route::delete('/shops/{shop}/favorite', 'destroy')->name('shops.favorite.destroy');

        // お気に入り状態取得
        Route::get('/shops/{shop}/favorite/status', 'status')->name('shops.favorite.status');
    });
});

// メンション機能用のユーザー検索ルート
Route::middleware(['auth'])->group(function () {
    Route::get('/users/search', [UserSearchController::class, 'search'])
        ->name('users.search')
        ->middleware('throttle:60,1');
});

// プロフィール機能のルート
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
