<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\FavoriteShopController;
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

// ★重要: お気に入り機能のルート（DELETEメソッド対応）★
Route::middleware(['auth'])->group(function () {
    // お気に入り追加（POST）
    Route::post('/shops/{shop}/favorite', [FavoriteShopController::class, 'store'])
        ->name('shops.favorite.store');

    // ★修正: お気に入り削除（DELETE）★
    Route::delete('/shops/{shop}/favorite', [FavoriteShopController::class, 'destroy'])
        ->name('shops.favorite.destroy');

    // お気に入り状態取得（GET）
    Route::get('/shops/{shop}/favorite/status', [FavoriteShopController::class, 'status'])
        ->name('shops.favorite.status');
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
