<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

// 既存のuseステートメント
use App\Models\Post;
use App\Models\Comment;
use App\Models\Folder;
use App\Models\Shop;

// ★追加：ProfileLinkのuse宣言★
use App\Models\ProfileLink;

class User extends Authenticatable
{
    // 既存のコード...

    // プロフィールリンク
    public function profileLinks(): HasMany
    {
        return $this->hasMany(ProfileLink::class);
    }

    // 残りのコード...
}
