<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return ! is_null($this->parent_id);
    }

    // 最上位のコメントのみ取得（返信ではないコメント）
    public function scopeParentComments($query)
    {
        return $query->whereNull('parent_id');
    }

    // メンション表示機能（アクセサ）- 日本語対応版＆XSS対策
    public function getBodyWithMentionsAttribute()
    {
        // まず危険なHTMLタグを除去（XSS対策）
        $safeBody = strip_tags($this->body);

        // HTMLエンティティに変換（追加の安全対策）
        $safeBody = htmlspecialchars($safeBody, ENT_QUOTES, 'UTF-8');

        // メンション部分だけをspan要素でラップ（安全なHTMLとして追加）
        // 日本語、英数字、アンダースコアに対応した正規表現
        $bodyWithMentions = preg_replace(
            '/@([a-zA-Z0-9_\p{L}\p{N}]+)/u',
            '<span style="color: #1976d2; font-weight: 500; background-color: #e3f2fd; padding: 2px 4px; border-radius: 3px;">@$1</span>',
            $safeBody
        );

        return $bodyWithMentions;
    }

    // このコメント配下の全ての返信を取得（YouTube風）
    public function getAllRepliesFlat()
    {
        $allReplies = collect();
        $this->collectAllReplies($this->id, $allReplies);

        return $allReplies->sortBy('created_at');
    }

    // 再帰的に全ての返信を収集
    private function collectAllReplies($parentId, &$collection)
    {
        $replies = Comment::where('parent_id', $parentId)->with('user', 'parent.user')->get();

        foreach ($replies as $reply) {
            $collection->push($reply);
            $this->collectAllReplies($reply->id, $collection);
        }
    }
}
