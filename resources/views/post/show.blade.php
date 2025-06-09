<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>show</title>
  <style>
    /* 既存のスタイルに追加 */

    /* ★ 新規追加: 店舗名リンクのスタイル ★ */
    .shop-link {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .shop-link:hover {
      color: #0056b3;
      text-decoration: underline;
    }

    .shop-icon {
      font-size: 1.1em;
    }

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

    .btn-info {
      background: #17a2b8;
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

    /* メンション検索ドロップダウン */
    .mention-dropdown {
      position: absolute;
      background: white;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      max-height: 200px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
    }

    .mention-item {
      padding: 8px 12px;
      cursor: pointer;
      font-size: 14px;
      border-bottom: 1px solid #f0f0f0;
    }

    .mention-item:hover,
    .mention-item.selected {
      background-color: #f0f0f0;
    }

    .mention-item:last-child {
      border-bottom: none;
    }

    .mention-loading {
      padding: 8px 12px;
      text-align: center;
      color: #666;
      font-size: 12px;
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
    <!-- ★ 修正: 店舗名を店舗詳細ページへのリンクに変更 ★ -->
    <h1>
      <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
        <span class="shop-icon">🏪</span>
        {{ $post->shop->name }}
      </a>
    </h1>

    <div class="post-info">
      <h3>基本情報</h3>
      <!-- ★ 修正: 住所も店舗詳細ページへのリンクに変更 ★ -->
      <p><strong>住所:</strong>
        <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
          {{ $post->shop->address }}
        </a>
      </p>
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
      <!-- ★ 新規追加: 店舗詳細ボタンを追加 ★ -->
      <a href="{{ route('shops.show', $post->shop->id) }}" class="btn btn-info">店舗詳細を見る</a>
      <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('削除しますか？')">削除</button>
      </form>
    </div>
  </div>

  <!-- コメントセクション（YouTube風） -->
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
      @forelse($post->comments()->parentComments()->with(['user'])->orderBy('created_at', 'desc')->get() as $comment)
      <!-- メインコメント -->
      <div class="comment" data-comment-id="{{ $comment->id }}">
        <div class="comment-meta">
          <strong>{{ $comment->user->name }}</strong>
          <span>{{ $comment->created_at->format('Y/m/d H:i') }}</span>
          @if(Auth::check() && (Auth::id() === $comment->user_id || Auth::id() === $post->user_id))
          <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: inline; float: right;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('削除しますか？')">削除</button>
          </form>
          @endif
        </div>
        <div class="comment-body">{!! $comment->body_with_mentions !!}</div>
        @auth
        <button class="reply-toggle-btn btn btn-secondary btn-small" data-comment-id="{{ $comment->id }}">返信</button>
        @endauth

        <!-- 返信フォーム -->
        @auth
        <div id="reply-form-{{ $comment->id }}" class="reply-form">
          <form action="{{ route('comments.store', $post->id) }}" method="POST" style="margin-top: 10px;">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="body" rows="2" placeholder="{{ $comment->user->name }}さんに返信...&#10;💡 @でスレッド参加者を検索" required></textarea>
            <div style="margin-top: 5px;">
              <button type="submit" class="btn btn-primary btn-small">返信投稿</button>
              <button type="button" class="reply-cancel-btn btn btn-secondary btn-small" data-comment-id="{{ $comment->id }}">キャンセル</button>
            </div>
          </form>
        </div>
        @endauth
      </div>

      <!-- このコメント配下の全ての返信（YouTube風に同階層で表示） -->
      @foreach($comment->getAllRepliesFlat() as $reply)
      <div class="comment reply" style="margin-left: 30px; border-left: 2px solid #eee; padding-left: 15px;" data-comment-id="{{ $reply->id }}">
        <div class="comment-meta">
          <strong>{{ $reply->user->name }}</strong>
          <span>{{ $reply->created_at->format('Y/m/d H:i') }}</span>
          @if($reply->parent && $reply->parent->user_id !== $comment->user_id)
          <span style="color: #666;">→ {{ $reply->parent->user->name }}さんへの返信</span>
          @endif
          @if(Auth::check() && (Auth::id() === $reply->user_id || Auth::id() === $post->user_id))
          <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" style="display: inline; float: right;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('削除しますか？')">削除</button>
          </form>
          @endif
        </div>
        <div class="comment-body">{!! $reply->body_with_mentions !!}</div>
        @auth
        <button class="reply-toggle-btn btn btn-secondary btn-small" data-comment-id="{{ $reply->id }}">返信</button>
        @endauth

        <!-- 返信に対する返信フォーム -->
        @auth
        <div id="reply-form-{{ $reply->id }}" class="reply-form">
          <form action="{{ route('comments.store', $post->id) }}" method="POST" style="margin-top: 10px;">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $reply->id }}">
            <textarea name="body" rows="2" placeholder="@{{ $reply->user->name }} さんに返信...&#10;💡 @でスレッド参加者を検索" required></textarea>
            <div style="margin-top: 5px;">
              <button type="submit" class="btn btn-primary btn-small">返信投稿</button>
              <button type="button" class="reply-cancel-btn btn btn-secondary btn-small" data-comment-id="{{ $reply->id }}">キャンセル</button>
            </div>
          </form>
        </div>
        @endauth
      </div>
      @endforeach

      @empty
      <p>まだコメントがありません。</p>
      @endforelse
    </div>
  </div>

  <!-- jQuery読み込み -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- 既存のJavaScriptコードは変更なし（省略） -->
  <script>
    // 既存のコメント機能のJavaScriptはそのまま使用
    // ここでは省略しますが、元のファイルのJavaScriptをそのまま使用してください
  </script>
</body>

</html>