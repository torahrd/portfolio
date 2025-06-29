<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Folder;
use App\Models\Shop;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'visit_time',
        'budget',
        'repeat_menu',
        'interest_menu',
        'reference_link',
        'memo',
        'visit_status',
        'private_status',
        'image_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ★修正: shop::class → Shop::class に変更
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function folders()
    {
        return $this->belongsToMany(Folder::class);
    }

    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'post_id', 'user_id');
    }

    /**
     * 指定されたユーザーがこの投稿にいいねしているかどうかを判定
     */
    public function isFavoritedBy($userId): bool
    {
        return $this->favorite_users()->where('user_id', $userId)->exists();
    }
}
