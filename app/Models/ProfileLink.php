<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProfileLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 有効なトークンかチェック
    public function isValid(): bool
    {
        return $this->is_active && $this->expires_at->isFuture();
    }

    // 新しいプロフィールリンクの生成
    public static function generateForUser(User $user): self
    {
        // 既存のアクティブなリンクを完全削除（セキュリティ強化）
        $user->profileLinks()->where('is_active', true)->delete();

        // 作成日時を組み込んだトークン生成
        $timestamp = now()->timestamp;
        $randomValue = Str::random(16);
        $token = hash('sha256', $user->id . $timestamp . $randomValue);

        return self::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addDays(3), // 3日間（72時間）の有効期限
            'is_active' => true
        ]);
    }

    // 期限切れ・無効化されたリンクの削除
    public static function cleanupExpiredLinks(): void
    {
        self::where('expires_at', '<', now())
            ->orWhere('is_active', false)
            ->delete();
    }

    // トークンで検索（有効なもののみ）
    public static function findValidToken(string $token): ?self
    {
        return self::where('token', $token)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
    }
}
