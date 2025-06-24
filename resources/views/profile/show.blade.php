<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $user->name }}のプロフィール</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- カスタムCSS -->
</head>

<body>
  <div class="container mt-4">
    <!-- メッセージ表示エリア -->
    <div id="message-area"></div>

    <!-- 戻るボタン -->
    <div class="mb-3">
      <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> 戻る
      </a>
    </div>

    <div class="row">
      <div class="col-md-4 mb-4">
        <!-- プロフィール情報セクション -->
        <div class="profile-card">
          <div class="profile-header text-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
              class="profile-avatar mb-3">
            <h1 class="profile-name">{{ $user->name }}</h1>

            @if($user->bio)
            <p class="profile-bio">{{ $user->bio }}</p>
            @endif

            <!-- プロフィール統計 -->
            <div class="profile-stats">
              <div class="stat-item">
                <span class="stat-number">{{ $user->posts_count }}</span>
                <span class="stat-label">投稿</span>
              </div>
              <div class="stat-item">
                <a href="{{ route('profile.followers', $user) }}" class="stat-link">
                  <span class="stat-number">{{ $user->followers_count }}</span>
                  <span class="stat-label">フォロワー</span>
                </a>
              </div>
              <div class="stat-item">
                <a href="{{ route('profile.following', $user) }}" class="stat-link">
                  <span class="stat-number">{{ $user->following_count }}</span>
                  <span class="stat-label">フォロー中</span>
                </a>
              </div>
            </div>

            <!-- フォローボタン -->
            @auth
            @if(auth()->id() !== $user->id)
            <div class="profile-actions mt-3">
              <button class="btn follow-btn {{ $isFollowing ? 'btn-primary following' : 'btn-outline-primary' }}"
                data-user-id="{{ $user->id }}"
                id="followButton{{ $user->id }}">
                @if($isFollowing)
                <span class="btn-text">フォロー中</span>
                @elseif($hasPendingRequest)
                <span class="btn-text">申請中</span>
                @else
                <span class="btn-text">フォロー</span>
                @endif
              </button>
            </div>
            @else
            <div class="profile-actions mt-3">
              <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                プロフィールを編集
              </a>
              @if($user->is_private)
              <button class="btn btn-outline-info mt-2" id="generateProfileLink">
                プロフィールリンクを生成
              </button>
              @endif
            </div>
            @endif
            @endauth

            <!-- 追加情報 -->
            <div class="profile-details text-start mt-3">
              @if($user->location)
              <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $user->location }}</span>
              </div>
              @endif

              @if($user->website)
              <div class="detail-item">
                <i class="fas fa-link"></i>
                <a href="{{ $user->website }}" target="_blank" rel="noopener">
                  {{ $user->website }}
                </a>
              </div>
              @endif

              <div class="detail-item">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ $user->created_at->format('Y年n月') }}に参加</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <!-- 投稿一覧 -->
        <div class="posts-section">
          <h2 class="section-title">投稿 ({{ $user->posts_count }}件)</h2>

          @if($posts->count() > 0)
          <div class="posts-grid">
            @foreach($posts as $post)
            <div class="post-item">
              <div class="post-header">
                <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}"
                  class="post-avatar">
                <div class="post-info">
                  <a href="{{ route('profile.show', $post->user) }}" class="post-author">
                    {{ $post->user->name }}
                  </a>
                  <span class="post-date">{{ $post->created_at->diffForHumans() }}</span>
                </div>
              </div>
              <div class="post-content">
                @if($post->shop)
                <h3 class="post-title">
                  <a href="{{ route('shops.show', $post->shop) }}">
                    {{ $post->shop->name }}
                  </a>
                </h3>
                <p class="post-location text-muted">
                  <i class="fas fa-map-marker-alt"></i>
                  {{ $post->shop->address }}
                </p>
                @endif

                @if($post->memo)
                <p class="post-excerpt">{{ Str::limit($post->memo, 100) }}</p>
                @endif

                @if($post->budget)
                <p class="post-budget">
                  <i class="fas fa-yen-sign"></i>
                  予算: {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
                </p>
                @endif

                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-outline-primary">
                  詳細を見る
                </a>
              </div>
            </div>
            @endforeach
          </div>

          <div class="mt-4">
            {{ $posts->links() }}
          </div>
          @else
          <div class="no-posts text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">まだ投稿がありません</p>
            @if(auth()->check() && auth()->id() === $user->id)
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
              最初の投稿を作成
            </a>
            @endif
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- プロフィールリンク生成モーダル -->
  <div class="modal fade" id="profileLinkModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">プロフィールリンクを生成</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>24時間有効なプロフィールリンクを生成します。このリンクを使用すると、フォローしていないユーザーでもあなたのプライベートプロフィールを閲覧できます。</p>
          <div id="profileLinkResult" style="display: none;">
            <div class="mb-3">
              <label class="form-label" for="profileLinkUrl">生成されたリンク:</label>
              <div class="input-group">
                <input type="text" class="form-control" id="profileLinkUrl" readonly>
                <button class="btn btn-outline-secondary" type="button" id="copyLinkBtn">
                  コピー
                </button>
              </div>
            </div>
            <small class="text-muted">
              有効期限: <span id="profileLinkExpiry"></span>
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
          <button type="button" class="btn btn-primary" id="generateLinkBtn">リンクを生成</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>