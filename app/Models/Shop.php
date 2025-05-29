<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Business_hours;
use App\Models\Post;

class Shop extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function business_hours()
    {
        return $this->hasMany(Business_hours::class);
    }
}
