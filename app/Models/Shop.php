<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Business_hours;
use App\Models\Post;
use App\Models\User;

class Shop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'reservation_url',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function business_hours()
    {
        return $this->hasMany(Business_hours::class);
    }

    /**
     * ★ 新規追加: この店舗をお気に入りにしているユーザーのリレーション ★
     */
    public function favorited_by_users()
    {
        return $this->belongsToMany(User::class, 'shop_favorites', 'shop_id', 'user_id')->withTimestamps();
    }

    /**
     * お気に入り数を取得
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorited_by_users()->count();
    }

    /**
     * 特定のユーザーがこの店舗をお気に入りしているかを判定
     */
    public function isFavoritedBy($userId)
    {
        return $this->favorited_by_users()->where('user_id', $userId)->exists();
    }

    /**
     * この店舗をお気に入りしているユーザーを最新順で取得
     */
    public function getFavoritedUsersOrdered()
    {
        return $this->favorited_by_users()
            ->withPivot('created_at')
            ->orderBy('shop_favorites.created_at', 'desc');
    }

    /**
     * お気に入り数でのスコープ（人気店舗の取得用）
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->withCount('favorited_by_users')
            ->orderBy('favorited_by_users_count', 'desc')
            ->limit($limit);
    }

    /**
     * 最近お気に入りされた店舗のスコープ
     */
    public function scopeRecentlyFavorited($query, $days = 7)
    {
        return $query->whereHas('favorited_by_users', function ($q) use ($days) {
            $q->where('shop_favorites.created_at', '>=', now()->subDays($days));
        });
    }
}
