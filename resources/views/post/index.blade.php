<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>投稿一覧</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- カスタムCSS -->
  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
  <link rel="stylesheet" href="{{ asset('css/posts.css') }}">
</head>

<body>
  <div class="container mt-4">
    <!-- メッセージ表示エリア -->
    <div id="message-area"></div>

    <!-- ヘッダー -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>
        <i class="fas fa-utensils text-primary"></i>
        投稿一覧
      </h1>
      @auth
      <a href="{{ route('profile.show', auth()->user()) }}" class="btn btn-outline-primary">
        <i class="fas fa-user"></i>
        マイプロフィール
      </a>
      @endauth
    </div>

    <!-- 投稿作成セクション -->
    @auth
    <div class="create-post-section">
      <h2>新しい店舗を投稿しよう！</h2>
      <p class="mb-3">あなたのお気に入りの店舗を仲間と共有しませんか？</p>
      <a href="{{ route('posts.create') }}" class="btn btn-lg">
        <i class="fas fa-plus"></i>
        投稿を作成
      </a>
    </div>
    @else
    <div class="alert alert-info text-center">
      <h5>ようこそ！</h5>
      <p class="mb-3">投稿を作成するにはログインが必要です</p>
      <a href="{{ route('login') }}" class="btn btn-primary me-2">ログイン</a>
      <a href="{{ route('register') }}" class="btn btn-outline-primary">新規登録</a>
    </div>
    @endauth

    <!-- 投稿一覧 -->
    <div class="posts">
      @forelse($posts as $post)
      <div class="post-item">
        <!-- 投稿者情報ヘッダー -->
        <div class="post-header">
          <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="post-avatar">

          <div class="post-author-info">
            <a href="{{ route('profile.show', $post->user) }}" class="post-author">
              {{ $post->user->name }}
              @if($post->user->is_private)
              <i class="fas fa-lock text-muted ms-1" title="プライベートアカウント"></i>
              @endif
            </a>
            <div class="post-meta">
              <i class="fas fa-clock"></i>
              {{ $post->created_at->diffForHumans() }}
              @if($post->visit_time)
              <span class="ms-2">
                <i class="fas fa-calendar-alt"></i>
                訪問: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y/m/d') }}
              </span>
              @endif
              <span class="visit-status-badge {{ $post->visit_status ? 'visit-status-visited' : 'visit-status-planned' }}">
                {{ $post->visit_status ? '訪問済み' : '訪問予定' }}
              </span>
            </div>
          </div>

          <!-- 投稿アクション（自分の投稿の場合） -->
          @auth
          @if(auth()->id() === $post->user_id)
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="fas fa-ellipsis-v"></i>
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

        <!-- 投稿コンテンツ -->
        <div class="post-content">
          <h3 class="mb-2">
            <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none">
              <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
                <i class="fas fa-store"></i>
                {{ $post->shop->name }}
              </a>
            </a>
          </h3>

          <p class="text-muted mb-2">
            <i class="fas fa-map-marker-alt"></i>
            <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
              {{ $post->shop->address }}
            </a>
          </p>

          @if($post->budget)
          <p class="mb-3">
            <strong>予算:</strong>
            <span class="budget">
              {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
            </span>
          </p>
          @endif

          @if($post->repeat_menu)
          <div class="mb-2">
            <strong><i class="fas fa-redo text-success"></i> リピートメニュー:</strong>
            {{ $post->repeat_menu }}
          </div>
          @endif

          @if($post->interest_menu)
          <div class="mb-2">
            <strong><i class="fas fa-star text-warning"></i> 気になるメニュー:</strong>
            {{ $post->interest_menu }}
          </div>
          @endif

          @if($post->memo)
          <div class="mb-3">
            <strong><i class="fas fa-sticky-note text-info"></i> メモ:</strong>
            {{ Str::limit($post->memo, 150) }}
            @if(strlen($post->memo) > 150)
            <a href="{{ route('posts.show', $post->id) }}" class="text-primary">続きを読む</a>
            @endif
          </div>
          @endif
        </div>

        <!-- 最新コメント表示 -->
        @if($post->comments->count() > 0)
        <div class="comments-section">
          <h6><i class="fas fa-comments"></i> 最新のコメント ({{ min($post->comments->count(), 3) }}件表示)</h6>
          @foreach($post->comments->take(3) as $comment)
          <div class="comment-item">
            <div class="d-flex align-items-center">
              <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}"
                class="rounded-circle me-2" style="width: 24px; height: 24px;">
              <span class="comment-author">{{ $comment->user->name }}</span>
              <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <div class="comment-body">{{ Str::limit($comment->body, 100) }}</div>
          </div>
          @endforeach

          @if($post->comments()->count() > 3)
          <div class="view-all-comments">
            <a href="{{ route('posts.show', $post->id) }}#comments">
              すべてのコメントを見る (全{{ $post->comments()->count() }}件)
            </a>
          </div>
          @endif
        </div>
        @else
        <div class="comments-section">
          <p class="text-muted mb-0">
            <i class="fas fa-comment-slash"></i>
            まだコメントがありません
          </p>
        </div>
        @endif

        <!-- アクションボタン -->
        <div class="actions mt-3">
          <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-eye"></i> 詳細を見る
          </a>
          @auth
          @if(auth()->id() === $post->user_id)
          <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-edit"></i> 編集
          </a>
          @endif
          @endauth
          <a href="{{ route('shops.show', $post->shop->id) }}" class="btn btn-outline-info btn-sm">
            <i class="fas fa-store"></i> 店舗詳細
          </a>
        </div>
      </div>
      @empty
      <!-- 投稿がない場合 -->
      <div class="text-center py-5">
        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
        <h3 class="text-muted">まだ投稿がありません</h3>
        <p class="text-muted mb-4">最初の投稿を作成して、お気に入りの店舗を共有しましょう！</p>
        @auth
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-plus"></i> 投稿を作成
        </a>
        @else
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-user-plus"></i> 今すぐ登録
        </a>
        @endauth
      </div>
      @endforelse
    </div>

    <!-- ページネーション -->
    @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->hasPages())
    <div class="d-flex justify-content-center mt-4">
      {{ $posts->links() }}
    </div>
    @endif
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- カスタムJS -->
  <script src="{{ asset('js/profile.js') }}"></script>

  <script>
    $(document).ready(function() {
      console.log('投稿一覧ページ初期化');

      // 投稿アイテムのアニメーション
      $('.post-item').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
      });

      // コメント表示の切り替え
      $('.view-all-comments a').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        window.location.href = href;
      });

      // 削除確認の改善
      $('form[action*="destroy"]').on('submit', function(e) {
        const shopName = $(this).closest('.post-item').find('.shop-link').first().text().trim();
        if (!confirm(`「${shopName}」の投稿を削除しますか？\n\nこの操作は取り消せません。`)) {
          e.preventDefault();
          return false;
        }
      });

      // 画像の遅延読み込み
      const images = document.querySelectorAll('img[data-src]');
      if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              const img = entry.target;
              img.src = img.dataset.src;
              img.classList.remove('lazy');
              imageObserver.unobserve(img);
            }
          });
        });

        images.forEach(img => imageObserver.observe(img));
      }
    });
  </script>
</body>

</html>