<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

// 既存のuseステートメント

// ★新規追加★

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',        // ★新規追加★
        'bio',           // ★新規追加★
        'location',      // ★新規追加★
        'website',       // ★新規追加★
        'is_private',     // ★新規追加★
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_private' => 'boolean',  // ★新規追加★
    ];

    protected $appends = ['avatar_url'];  // ★新規追加★

    // ===== 既存のリレーション（そのまま保持） =====
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

    public function favorite_shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_favorites', 'user_id', 'shop_id')->withTimestamps();
    }

    // ===== 新規追加：フォロー機能のリレーション =====

    // フォロワー関係（自分をフォローしているユーザー）
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'followed_id',    // 自分がフォローされる側
            'following_id'    // 相手がフォローする側
        )->withPivot(['status', 'created_at'])
            ->wherePivot('status', 'active');
    }

    // フォロー中の関係（自分がフォローしているユーザー）
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'following_id',   // 自分がフォローする側
            'followed_id'     // 相手がフォローされる側
        )->withPivot(['status', 'created_at'])
            ->wherePivot('status', 'active');
    }

    // フォロー申請（保留中）- 自分への申請
    public function pendingFollowRequests(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'followed_id',    // 自分がフォローされる側
            'following_id'    // 相手がフォローする側
        )->withPivot(['status', 'created_at'])
            ->wherePivot('status', 'pending');
    }

    // 自分が送った保留中の申請
    public function sentFollowRequests(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'following_id',   // 自分がフォローする側
            'followed_id'     // 相手がフォローされる側
        )->withPivot(['status', 'created_at'])
            ->wherePivot('status', 'pending');
    }

    // プロフィールリンク
    public function profileLinks(): HasMany
    {
        return $this->hasMany(ProfileLink::class);
    }

    // ===== 新規追加：アクセサー =====
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar
            ? Storage::url('avatars/'.$this->avatar)
            : null;
    }

    // ===== 新規追加：フォロー状態チェックメソッド =====
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('following_id', $user->id)->exists();
    }

    public function hasPendingFollowRequest(User $user): bool
    {
        return $this->pendingFollowRequests()->where('following_id', $user->id)->exists();
    }

    public function hasSentFollowRequest(User $user): bool
    {
        return $this->sentFollowRequests()->where('followed_id', $user->id)->exists();
    }

    // ===== 新規追加：フォロー操作メソッド =====
    public function follow(User $user): void
    {
        if (! $this->isFollowing($user) && ! $this->hasSentFollowRequest($user) && $this->id !== $user->id) {
            $status = $user->is_private ? 'pending' : 'active';

            $this->following()->attach($user->id, [
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // カウント更新（アクティブフォローのみ）
            if ($status === 'active') {
                $this->increment('following_count');
                $user->increment('followers_count');
            }
        }
    }

    public function unfollow(User $user): void
    {
        if ($this->isFollowing($user) || $this->hasSentFollowRequest($user)) {
            $wasActive = $this->following()->where('followed_id', $user->id)->wherePivot('status', 'active')->exists();

            $this->following()->detach($user->id);

            // アクティブフォローだった場合のみカウント更新
            if ($wasActive) {
                $this->decrement('following_count');
                $user->decrement('followers_count');
            }
        }
    }

    // フォロー申請の承認・拒否
    public function acceptFollowRequest(User $user): void
    {
        // 「pending」状態のpivotのみ「active」に更新
        $this->followers()
            ->wherePivot('status', 'pending')
            ->updateExistingPivot($user->id, [
                'status' => 'active',
                'updated_at' => now(),
            ]);

        $this->increment('followers_count');
        $user->increment('following_count');
    }

    public function rejectFollowRequest(User $user): void
    {
        // 「pending」状態のpivotのみ削除
        $this->followers()
            ->wherePivot('status', 'pending')
            ->detach($user->id);
    }

    // ===== 既存メソッドの維持（変更なし） =====
    public function hasFavoriteShop($shop): bool
    {
        $shopId = is_object($shop) ? $shop->id : $shop;

        return $this->favorite_shops()->where('shop_id', $shopId)->exists();
    }

    public function addFavoriteShop($shop): bool
    {
        $shopId = is_object($shop) ? $shop->id : $shop;

        if ($this->hasFavoriteShop($shopId)) {
            return false;
        }

        $this->favorite_shops()->attach($shopId);

        return true;
    }

    public function removeFavoriteShop($shop): bool
    {
        $shopId = is_object($shop) ? $shop->id : $shop;

        if (! $this->hasFavoriteShop($shopId)) {
            return false;
        }

        $this->favorite_shops()->detach($shopId);

        return true;
    }

    /**
     * パスワードリセット通知の送信
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
