<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(shop::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function my_lists()
    {
        return $this->hasMany(My_list::class);
    }

    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favoties', 'post_id', 'user_id')->withTimestamps();
    }
}
