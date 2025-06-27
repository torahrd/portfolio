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
        'latitude',
        'longitude',
        'formatted_phone_number',
        'website',
        'google_place_id',
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

    /**
     * Google Place IDで店舗を検索するスコープ
     */
    public function scopeByGooglePlaceId($query, $placeId)
    {
        return $query->where('google_place_id', $placeId);
    }

    /**
     * Google Place IDが設定されている店舗を取得するスコープ
     */
    public function scopeWithGooglePlaceId($query)
    {
        return $query->whereNotNull('google_place_id');
    }

    /**
     * Google Place IDが設定されていない店舗を取得するスコープ
     */
    public function scopeWithoutGooglePlaceId($query)
    {
        return $query->whereNull('google_place_id');
    }

    /**
     * 座標が設定されている店舗を取得するスコープ
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * 指定された座標から一定距離内の店舗を取得するスコープ
     */
    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 10)
    {
        return $query->withCoordinates()
            ->selectRaw(
                '*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }

    /**
     * Google Places APIから取得した情報で店舗を更新
     */
    public function updateFromGooglePlaces(array $placeData): bool
    {
        $updateData = [];

        // 基本情報の更新
        if (isset($placeData['name'])) {
            $updateData['name'] = $placeData['name'];
        }

        if (isset($placeData['formatted_address'])) {
            $updateData['address'] = $placeData['formatted_address'];
        }

        if (isset($placeData['formatted_phone_number'])) {
            $updateData['formatted_phone_number'] = $placeData['formatted_phone_number'];
        }

        if (isset($placeData['website'])) {
            $updateData['website'] = $placeData['website'];
        }

        // 座標情報の更新
        if (isset($placeData['geometry']['location'])) {
            $location = $placeData['geometry']['location'];
            $updateData['latitude'] = $location['lat'];
            $updateData['longitude'] = $location['lng'];
        }

        // Place IDの設定
        if (isset($placeData['place_id'])) {
            $updateData['google_place_id'] = $placeData['place_id'];
        }

        return $this->update($updateData);
    }

    /**
     * Google Places APIから営業時間を取得してbusiness_hoursテーブルに保存
     */
    public function updateBusinessHoursFromGooglePlaces(array $placeData): bool
    {
        if (!isset($placeData['opening_hours']['periods'])) {
            return false;
        }

        // 既存の営業時間を削除
        $this->business_hours()->delete();

        $periods = $placeData['opening_hours']['periods'];
        $businessHours = [];

        foreach ($periods as $period) {
            if (isset($period['open']) && isset($period['close'])) {
                $businessHours[] = [
                    'shop_id' => $this->id,
                    'day' => $period['open']['day'],
                    'open_time' => $this->formatGoogleTime($period['open']['time']),
                    'close_time' => $this->formatGoogleTime($period['close']['time']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($businessHours)) {
            return $this->business_hours()->insert($businessHours);
        }

        return false;
    }

    /**
     * Google Places APIの時間形式（HHMM）をMySQLのtime形式（HH:MM:SS）に変換
     */
    private function formatGoogleTime(string $googleTime): string
    {
        $hour = substr($googleTime, 0, 2);
        $minute = substr($googleTime, 2, 2);
        return sprintf('%02d:%02d:00', $hour, $minute);
    }

    /**
     * 同じGoogle Place IDを持つ店舗が存在するかチェック
     */
    public static function existsByGooglePlaceId(string $placeId): bool
    {
        return static::where('google_place_id', $placeId)->exists();
    }

    /**
     * Google Place IDで店舗を取得
     */
    public static function findByGooglePlaceId(string $placeId): ?self
    {
        return static::where('google_place_id', $placeId)->first();
    }
}
