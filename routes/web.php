<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
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

Route::controller(PostController::class)->middleware(['auth'])->group(function () {
    Route::get('/posts', 'index')->name('posts.index');
    Route::get('/posts/create', 'create')->name('posts.create');
    Route::post('/posts', 'store')->name('posts.store');
    Route::get('/posts/{post}', 'show')->name('posts.show');
    Route::put('/posts/{post}', 'update')->name('posts.update');
    Route::get('/posts/{post}/edit', 'edit')->name('posts.edit');
    Route::delete('/posts/{post}', 'destroy')->name('posts.destroy');
});

Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    Route::get('/users', 'index')->name('user');
});

Route::controller(CommentController::class)->middleware(['auth'])->group(function () {
    Route::get('/comments/{post}', 'index')->name('comments.index');
    Route::get('/comments/{post}/create', 'create')->name('comments.create');
    Route::post('/comments/{post}', 'sotore')->name('comments.store');
    Route::get('/commnets/{post}/show', 'show')->name('comments.show');
    Route::put('/comments/{post}', 'update')->name('comments.update');
    Route::get('/comments/{post}/edit', 'edit')->name('comments.edit');
    Route::delete('/comments/{post}', 'destroy')->name('comments.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
