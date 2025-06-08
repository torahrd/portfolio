<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Folder;
use App\Models\Shop;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function favorite_posts()
    {
        return $this->belongsToMany(Post::class, 'favorites', 'user_id', 'post_id')->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'followed_id')->withTimestamps();
    }

    public function followed()
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'following_id')->withTimestamps();
    }

    /**
     * ★ 新規追加: お気に入り店舗のリレーション ★
     */
    public function favorite_shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_favorites', 'user_id', 'shop_id')->withTimestamps();
    }

    /**
     * 特定の店舗がお気に入りかどうかを判定
     */
    public function hasFavoriteShop($shopId)
    {
        return $this->favorite_shops()->where('shop_id', $shopId)->exists();
    }

    /**
     * 店舗をお気に入りに追加
     */
    public function addFavoriteShop($shopId)
    {
        if (!$this->hasFavoriteShop($shopId)) {
            return $this->favorite_shops()->attach($shopId);
        }
        return false;
    }

    /**
     * 店舗をお気に入りから削除
     */
    public function removeFavoriteShop($shopId)
    {
        return $this->favorite_shops()->detach($shopId);
    }

    /**
     * お気に入り店舗をお気に入り追加日時順で取得
     */
    public function getFavoriteShopsOrdered()
    {
        return $this->favorite_shops()
            ->withPivot('created_at')
            ->orderBy('shop_favorites.created_at', 'desc');
    }
}
