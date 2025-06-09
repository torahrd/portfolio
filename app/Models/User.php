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

    // 既存のリレーション
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

    // ★重要: お気に入り店舗のリレーション★
    public function favorite_shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_favorites', 'user_id', 'shop_id')->withTimestamps();
    }

    /**
     * ★重要: 特定の店舗がお気に入りかどうかを判定★
     * 
     * @param int|Shop $shop 店舗IDまたは店舗モデル
     * @return bool
     */
    public function hasFavoriteShop($shop): bool
    {
        $shopId = is_object($shop) ? $shop->id : $shop;

        return $this->favorite_shops()->where('shop_id', $shopId)->exists();
    }

    /**
     * ★重要: 店舗をお気に入りに追加★
     * 
     * @param int|Shop $shop 店舗IDまたは店舗モデル
     * @return bool 追加が成功したかどうか
     */
    public function addFavoriteShop($shop): bool
    {
        $shopId = is_object($shop) ? $shop->id : $shop;

        // 既にお気に入りに登録済みの場合は何もしない
        if ($this->hasFavoriteShop($shopId)) {
            return false;
        }

        // お気に入りに追加
        $this->favorite_shops()->attach($shopId);

        return true;
    }

    /**
     * ★重要: 店舗をお気に入りから削除★
     * 
     * @param int|Shop $shop 店舗IDまたは店舗モデル
     * @return bool 削除が成功したかどうか
     */
    public function removeFavoriteShop($shop): bool
    {
        $shopId = is_object($shop) ? $shop->id : $shop;

        // お気に入りに登録されていない場合は何もしない
        if (!$this->hasFavoriteShop($shopId)) {
            return false;
        }

        // お気に入りから削除
        $this->favorite_shops()->detach($shopId);

        return true;
    }

    /**
     * お気に入り店舗をお気に入り追加日時順で取得
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getFavoriteShopsOrdered()
    {
        return $this->favorite_shops()
            ->withPivot('created_at')
            ->orderBy('shop_favorites.created_at', 'desc');
    }

    /**
     * ユーザーのお気に入り店舗数を取得
     * 
     * @return int
     */
    public function getFavoriteShopsCountAttribute(): int
    {
        return $this->favorite_shops()->count();
    }
}
