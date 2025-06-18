<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'phone',
        'website',
        'description',
        'category',
        'latitude',
        'longitude',
        'created_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * この店舗を作成したユーザー
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * この店舗の投稿
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 最近の投稿
     */
    public function recentPosts(): HasMany
    {
        return $this->posts()->latest()->limit(10);
    }

    /**
     * 営業時間との関連
     */
    public function business_hours()
    {
        return $this->hasMany(Business_hours::class);
    }

    /**
     * ★ この店舗をお気に入りにしているユーザーのリレーション ★
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
        if (!$userId) return false;
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
     * ★ 新規追加: 投稿データから平均予算を計算 ★
     */
    public function getAverageBudgetAttribute()
    {
        $avgBudget = $this->posts()->whereNotNull('budget')->avg('budget');
        return $avgBudget ? round($avgBudget) : null;
    }

    /**
     * ★ 新規追加: 平均予算を表示用文字列で取得 ★
     */
    public function getFormattedAverageBudgetAttribute()
    {
        $avg = $this->average_budget;
        if (!$avg) return '予算情報なし';

        return \App\Helpers\BudgetHelper::formatBudget($avg);
    }

    /**
     * ★ 新規追加: 今日の営業時間を取得 ★
     */
    public function getTodayBusinessHoursAttribute()
    {
        $today = Carbon::now()->dayOfWeek; // 0=日曜日, 1=月曜日, ...
        return $this->business_hours()->where('day', $today)->first();
    }

    /**
     * ★ 新規追加: 現在営業中かどうかを判定 ★
     */
    public function getIsOpenNowAttribute()
    {
        $todayHours = $this->today_business_hours;
        if (!$todayHours) return false;

        $now = Carbon::now()->format('H:i');
        $openTime = Carbon::parse($todayHours->open_time)->format('H:i');
        $closeTime = Carbon::parse($todayHours->close_time)->format('H:i');

        // 営業時間内かチェック
        if ($closeTime > $openTime) {
            // 通常の営業時間（例: 09:00-17:00）
            return $now >= $openTime && $now <= $closeTime;
        } else {
            // 深夜営業（例: 22:00-02:00）
            return $now >= $openTime || $now <= $closeTime;
        }
    }

    /**
     * ★ 新規追加: 営業ステータスを文字列で取得 ★
     */
    public function getOpenStatusAttribute()
    {
        $todayHours = $this->today_business_hours;
        if (!$todayHours) return '営業時間不明';

        return $this->is_open_now ? '営業中' : '営業時間外';
    }

    /**
     * ★ 新規追加: 最近の投稿を取得（5件） ★
     */
    public function getRecentPostsAttribute()
    {
        return $this->posts()
            ->with(['user:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
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
