<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>show</title>
  <style>
    /* 既存のスタイルに追加 */
    .comment-section {
      margin-top: 30px;
      padding: 20px;
      border-top: 2px solid #eee;
    }

    .comment {
      background: #f9f9f9;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 5px;
      border-left: 3px solid #007bff;
    }

    .comment.reply {
      margin-left: 30px;
      border-left-color: #28a745;
      background: #f0f8f0;
    }

    .comment.level-1 {
      margin-left: 20px;
      border-left-color: #28a745;
      background: #f0f8f0;
    }

    .comment.level-2 {
      margin-left: 40px;
      border-left-color: #ffc107;
      background: #fffbf0;
    }

    .comment.level-3 {
      margin-left: 60px;
      border-left-color: #fd7e14;
      background: #fff5f0;
    }

    .comment.level-4 {
      margin-left: 80px;
      border-left-color: #dc3545;
      background: #fdf0f0;
    }

    .comment.level-5-plus {
      margin-left: 100px;
      border-left-color: #6f42c1;
      background: #f8f0ff;
    }

    .comment-form {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .reply-form {
      margin-top: 10px;
      display: none;
      background: #fff;
      padding: 10px;
      border-radius: 3px;
    }

    .btn {
      padding: 8px 12px;
      margin: 2px;
      text-decoration: none;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-size: 14px;
    }

    .btn-primary {
      background: #007bff;
      color: white;
    }

    .btn-secondary {
      background: #6c757d;
      color: white;
    }

    .btn-danger {
      background: #dc3545;
      color: white;
    }

    .btn-small {
      padding: 4px 8px;
      font-size: 12px;
    }

    .comment-meta {
      font-size: 12px;
      color: #666;
      margin-bottom: 5px;
    }

    .comment-body {
      margin-bottom: 10px;
      line-height: 1.4;
    }

    .badge {
      background: #007bff;
      color: white;
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 12px;
      margin-right: 5px;
    }

    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      resize: vertical;
    }
  </style>
</head>

<body>
  <!-- セッションメッセージの表示 -->
  @if (session('success'))
  <div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px; border-radius: 4px;">
    {{ session('success') }}
  </div>
  @endif

  <div class="post-detail">
    <h1>{{ $post->shop->name }}</h1>

    <div class="post-info">
      <h3>基本情報</h3>
      <p><strong>住所:</strong> {{ $post->shop->address }}</p>
      <p><strong>訪問日時:</strong> {{ $post->visit_time }}</p>
      <p><strong>訪問済:</strong> {{ $post->visit_status ? 'はい' : 'いいえ' }}</p>
      <p><strong>予算:</strong> {{ number_format($post->budget) }}円</p>
    </div>

    <div class="menus">
      <h3>メニュー情報</h3>
      <p><strong>リピートメニュー:</strong> {{ $post->repeat_menu }}</p>
      <p><strong>気になるメニュー:</strong> {{ $post->interest_menu }}</p>
    </div>

    <div class="memo">
      <h3>メモ</h3>
      <p><strong>メモ:</strong> {{ $post->memo }}</p>
    </div>

    @if($post->reference_link)
    <div class="reference">
      <h3>参考リンク</h3>
      <a href="{{ $post->reference_link }}" target="_blank">{{ $post->reference_link }}</a>
    </div>
    @endif

    <div class="folders">
      <h3>所属フォルダ</h3>
      @if(Auth::check() && $post->user_id === Auth::id())
      @if($post->folders->count() > 0)
      @foreach($post->folders as $folder)
      <span class="badge">{{ $folder->name }}</span>
      @endforeach
      @else
      <p>フォルダに登録されていません</p>
      @endif
      @else
      <p>フォルダ情報は投稿者のみ表示されます</p>
      @endif
    </div>

    <div class="actions">
      <a href="{{ route('posts.index') }}" class="btn btn-secondary">一覧に戻る</a>
      <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">編集</a>
      <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('削除しますか？')">削除</button>
      </form>
    </div>
  </div>

  <!-- コメントセクション（新規追加部分） -->
  <div class="comment-section">
    <h3>コメント ({{ $post->comments->where('parent_id', null)->count() }})</h3>

    <!-- コメント投稿フォーム -->
    @auth
    <div class="comment-form">
      <h4>コメントを投稿</h4>
      <form action="{{ route('comments.store', $post->id) }}" method="POST">
        @csrf
        <div style="margin-bottom: 10px;">
          <textarea name="body" rows="3" placeholder="コメントを入力してください..." required>{{ old('body') }}</textarea>
          @error('body')
          <div style="color: red; font-size: 12px;">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">コメント投稿</button>
      </form>
    </div>
    @else
    <p><a href="{{ route('login') }}">ログイン</a>してコメントを投稿できます。</p>
    @endauth

    <!-- コメント一覧 -->
    <div class="comments">
      @forelse($post->comments()->parentComments()->with(['user', 'childrenRecursive.user'])->orderBy('created_at', 'desc')->get() as $comment)
      @include('post.partials.comment', ['comment' => $comment, 'post' => $post, 'level' => 0])
      @empty
      <p>まだコメントがありません。</p>
      @endforelse
    </div>
  </div>

  <script>
    function toggleReplyForm(commentId) {
      const form = document.getElementById('reply-form-' + commentId);
      if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        form.querySelector('textarea').focus();
      } else {
        form.style.display = 'none';
      }
    }
  </script>
</body>

</html>