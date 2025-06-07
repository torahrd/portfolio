<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // 親コメント（返信先のコメント）
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // 子コメント（このコメントへの返信）
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // 返信かどうかを判定
    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    // 最上位のコメントのみ取得（返信ではないコメント）
    public function scopeParentComments($query)
    {
        return $query->whereNull('parent_id');
    }

    // 子コメントを再帰的に取得（すべての階層の返信）
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    // インデントレベルを計算
    public function getIndentLevel()
    {
        $level = 0;
        $current = $this;

        while ($current->parent_id) {
            $level++;
            $current = $current->parent;
        }

        return $level;
    }
}
