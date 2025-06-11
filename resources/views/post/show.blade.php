<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $post->shop->name }} - 投稿詳細</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- カスタムCSS -->
  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
  <link rel="stylesheet" href="{{ asset('css/posts.css') }}">
</head>

<body>
  <!-- セッションメッセージの表示 -->
  @if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="container mt-4">
    <!-- 戻るボタン -->
    <div class="mb-3">
      <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> 投稿一覧に戻る
      </a>
    </div>

    <div class="post-detail">
      <!-- 投稿者情報ヘッダー -->
      <div class="post-detail-header">
        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="post-detail-avatar">

        <div class="post-detail-author-info">
          <h2>
            <a href="{{ route('profile.show', $post->user) }}">
              {{ $post->user->name }}
              @if($post->user->is_private)
              <i class="fas fa-lock text-muted ms-1" title="プライベートアカウント"></i>
              @endif
            </a>
          </h2>
          <div class="post-detail-meta">
            <i class="fas fa-clock"></i>
            {{ $post->created_at->format('Y年n月j日 H:i') }}
            <span class="ms-2">
              <i class="fas fa-eye"></i>
              投稿詳細
            </span>
            @if($post->visit_time)
            <span class="ms-2">
              <i class="fas fa-calendar-alt"></i>
              訪問: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y年n月j日') }}
            </span>
            @endif
            <span class="ms-2 badge {{ $post->visit_status ? 'bg-success' : 'bg-warning' }}">
              {{ $post->visit_status ? '訪問済み' : '訪問予定' }}
            </span>
          </div>
        </div>

        <!-- 投稿アクション（自分の投稿の場合） -->
        @auth
        @if(auth()->id() === $post->user_id)
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-cog"></i> 設定
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">
                <i class="fas fa-edit"></i> 編集
              </a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('削除しますか？')">
                  <i class="fas fa-trash"></i> 削除
                </button>
              </form>
            </li>
          </ul>
        </div>
        @endif
        @endauth
      </div>

      <!-- 店舗情報 -->
      <h1>
        <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
          <span class="shop-icon">🏪</span>
          {{ $post->shop->name }}
        </a>
      </h1>

      <div class="post-info">
        <h3><i class="fas fa-info-circle"></i> 基本情報</h3>
        <p><strong>住所:</strong>
          <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
            {{ $post->shop->address }}
          </a>
        </p>
        <p><strong>訪問日時:</strong> {{ $post->visit_time ? \Carbon\Carbon::parse($post->visit_time)->format('Y年n月j日 H:i') : '未設定' }}</p>
        <p><strong>訪問済:</strong> {{ $post->visit_status ? 'はい' : 'いいえ' }}</p>
        @if($post->budget)
        <p><strong>予算:</strong> {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}</p>
        @endif
      </div>

      @if($post->repeat_menu || $post->interest_menu)
      <div class="menus">
        <h3><i class="fas fa-utensils"></i> メニュー情報</h3>
        @if($post->repeat_menu)
        <p><strong>リピートメニュー:</strong> {{ $post->repeat_menu }}</p>
        @endif
        @if($post->interest_menu)
        <p><strong>気になるメニュー:</strong> {{ $post->interest_menu }}</p>
        @endif
      </div>
      @endif

      @if($post->memo)
      <div class="memo">
        <h3><i class="fas fa-sticky-note"></i> メモ</h3>
        <p>{{ $post->memo }}</p>
      </div>
      @endif

      @if($post->reference_link)
      <div class="reference">
        <h3><i class="fas fa-link"></i> 参考リンク</h3>
        <a href="{{ $post->reference_link }}" target="_blank" rel="noopener">{{ $post->reference_link }}</a>
      </div>
      @endif

      <div class="folders">
        <h3><i class="fas fa-folder"></i> 所属フォルダ</h3>
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
        @auth
        @if(auth()->id() === $post->user_id)
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">編集</a>
        @endif
        @endauth
        <a href="{{ route('shops.show', $post->shop->id) }}" class="btn btn-info">店舗詳細を見る</a>
        <a href="{{ route('profile.show', $post->user) }}" class="btn btn-outline-primary">
          <i class="fas fa-user"></i> {{ $post->user->name }}のプロフィール
        </a>
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
            <div class="d-flex align-items-center">
              <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}"
                class="rounded-circle me-2" style="width: 32px; height: 32px;">
              <strong>{{ $comment->user->name }}</strong>
              <span class="ms-2">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
              @if(Auth::check() && (Auth::id() === $comment->user_id || Auth::id() === $post->user_id))
              <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: inline; margin-left: auto;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('削除しますか？')">削除</button>
              </form>
              @endif
            </div>
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
            <div class="d-flex align-items-center">
              <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}"
                class="rounded-circle me-2" style="width: 28px; height: 28px;">
              <strong>{{ $reply->user->name }}</strong>
              <span class="ms-2">{{ $reply->created_at->format('Y/m/d H:i') }}</span>
              @if($reply->parent && $reply->parent->user_id !== $comment->user_id)
              <span style="color: #666; margin-left: 8px;">→ {{ $reply->parent->user->name }}さんへの返信</span>
              @endif
              @if(Auth::check() && (Auth::id() === $reply->user_id || Auth::id() === $post->user_id))
              <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" style="display: inline; margin-left: auto;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('削除しますか？')">削除</button>
              </form>
              @endif
            </div>
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
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- カスタムJS -->
  <script src="{{ asset('js/profile.js') }}"></script>

  <script>
    $(document).ready(function() {
      console.log('投稿詳細ページ初期化');

      // 返信フォームの表示/非表示
      $('.reply-toggle-btn').on('click', function() {
        const commentId = $(this).data('comment-id');
        const $replyForm = $(`#reply-form-${commentId}`);

        if ($replyForm.is(':visible')) {
          $replyForm.hide();
        } else {
          // 他の返信フォームを閉じる
          $('.reply-form').hide();
          $replyForm.show();
          $replyForm.find('textarea').focus();
        }
      });

      // 返信キャンセル
      $('.reply-cancel-btn').on('click', function() {
        const commentId = $(this).data('comment-id');
        $(`#reply-form-${commentId}`).hide();
      });

      // アバター画像のエラーハンドリング
      $('img').on('error', function() {
        $(this).attr('src', 'https://via.placeholder.com/60x60/cccccc/ffffff?text=No+Image');
      });

      // 削除確認の改善
      $('form[action*="destroy"]').on('submit', function(e) {
        const isComment = $(this).find('button').hasClass('btn-small');
        const confirmMessage = isComment ?
          'コメントを削除しますか？\n\nこの操作は取り消せません。' :
          '投稿を削除しますか？\n\nこの操作は取り消せません。';

        if (!confirm(confirmMessage)) {
          e.preventDefault();
          return false;
        }
      });
    });
  </script>
</body>

</html>