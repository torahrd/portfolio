@php
// インデントレベルに応じたCSSクラスを決定
$levelClass = '';
if ($level > 0) {
if ($level <= 4) {
  $levelClass="level-{$level}" ;
  } else {
  $levelClass='level-5-plus' ;
  }
  }

  // 返信先のユーザー名を取得
  $replyToUser=$comment->parent ? $comment->parent->user->name : null;
  @endphp

  <div class="comment {{ $levelClass }}">
    <div class="comment-meta">
      <strong>{{ $comment->user->name }}</strong>
      <span>{{ $comment->created_at->format('Y/m/d H:i') }}</span>

      @if($replyToUser)
      <span style="color: #666;">→ {{ $replyToUser }}さんへの返信</span>
      @endif

      @if(Auth::check() && (Auth::id() === $comment->user_id || Auth::id() === $post->user_id))
      <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: inline; float: right;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('削除しますか？')">削除</button>
      </form>
      @endif
    </div>

    <div class="comment-body">{{ $comment->body }}</div>

    @auth
    <button onclick="toggleReplyForm(<?php echo $comment->id; ?>)" class="btn btn-secondary btn-small">返信</button>
    @endauth

    <!-- 返信フォーム -->
    @auth
    <div id="reply-form-<?php echo $comment->id; ?>" class="reply-form">
      <form action="{{ route('comments.store', $post->id) }}" method="POST" style="margin-top: 10px;">
        @csrf
        <input type="hidden" name="parent_id" value="<?php echo $comment->id; ?>">
        <textarea name="body" rows="2" placeholder="<?php echo $comment->user->name; ?>さんに返信..." required></textarea>
        <div style="margin-top: 5px;">
          <button type="submit" class="btn btn-primary btn-small">返信投稿</button>
          <button type="button" onclick="toggleReplyForm(<?php echo $comment->id; ?>)" class="btn btn-secondary btn-small">キャンセル</button>
        </div>
      </form>
    </div>
    @endauth
  </div>

  <!-- 子コメントを再帰的に表示 -->
  @foreach($comment->children()->with('user')->orderBy('created_at', 'asc')->get() as $childComment)
  @include('post.partials.comment', [
  'comment' => $childComment,
  'post' => $post,
  'level' => $level + 1
  ])
  @endforeach