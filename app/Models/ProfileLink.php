<?php
// app/Models/ProfileLink.php

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
        // 既存のリンクを無効化
        $user->profileLinks()->update(['is_active' => false]);

        return self::create([
            'user_id' => $user->id,
            'token' => Str::random(32),
            'expires_at' => now()->addHours(24),
            'is_active' => true
        ]);
    }
}
