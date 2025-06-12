<x-app-layout>
  <x-slot name="header">
    <div class="flex justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        <i class="fas fa-utensils mr-2"></i>
        投稿一覧
      </h2>
      @auth
      <a href="{{ route('profile.show', auth()->user()) }}" class="btn btn-outline-primary">
        <i class="fas fa-user mr-2"></i>
        マイプロフィール
      </a>
      @endauth
    </div>
  </x-slot>

  <div class="container-responsive py-6">
    <!-- メッセージ表示エリア -->
    <div id="message-area"></div>

    <!-- 投稿作成セクション -->
    @auth
    <div class="create-post-section">
      <h2>新しい店舗を投稿しよう！</h2>
      <p class="mb-4">あなたのお気に入りの店舗を仲間と共有しませんか？</p>
      <a href="{{ route('posts.create') }}" class="btn">
        <i class="fas fa-plus mr-2"></i>
        投稿を作成
      </a>
    </div>
    @else
    <div class="alert alert-info text-center">
      <h5 class="text-xl font-semibold mb-2">ようこそ！</h5>
      <p class="mb-4">投稿を作成するにはログインが必要です</p>
      <div class="space-x-2">
        <a href="{{ route('login') }}" class="btn btn-primary">ログイン</a>
        <a href="{{ route('register') }}" class="btn btn-outline-primary">新規登録</a>
      </div>
    </div>
    @endauth

    <!-- 投稿一覧 -->
    <div class="space-y-6">
      @forelse($posts as $post)
      <div class="post-item">
        <!-- 投稿者情報ヘッダー -->
        <div class="post-header">
          <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="post-avatar">

          <div class="post-author-info">
            <a href="{{ route('profile.show', $post->user) }}" class="post-author">
              {{ $post->user->name }}
              @if($post->user->is_private)
              <i class="fas fa-lock text-gray-400 ml-1" title="プライベートアカウント"></i>
              @endif
            </a>
            <div class="post-meta">
              <span><i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
              @if($post->visit_time)
              <span><i class="fas fa-calendar-alt"></i> 訪問: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y/m/d') }}</span>
              @endif
              <span class="visit-status-badge {{ $post->visit_status ? 'visit-status-visited' : 'visit-status-planned' }}">
                {{ $post->visit_status ? '訪問済み' : '行きたい' }}
              </span>
            </div>
          </div>
        </div>

        <!-- 投稿内容 -->
        <div class="space-y-4">
          <h3 class="text-xl font-semibold">
            <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
              <i class="fas fa-store shop-icon"></i>
              {{ $post->shop->name }}
            </a>
          </h3>

          @if($post->body)
          <p class="text-gray-700 leading-relaxed">{{ Str::limit($post->body, 200) }}</p>
          @endif

          @if($post->budget)
          <p class="budget">
            予算: {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
          </p>
          @endif
        </div>

        <!-- コメントプレビュー -->
        @if($post->comments()->count() > 0)
        <div class="comments-section">
          <h4 class="font-semibold text-gray-700 mb-3">
            <i class="fas fa-comments mr-2"></i>
            コメント ({{ $post->comments()->count() }}件)
          </h4>
          @foreach($post->comments()->with('user')->limit(3)->get() as $comment)
          <div class="comment-item">
            <div class="flex items-start space-x-2">
              <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-6 h-6 rounded-full">
              <div class="flex-1">
                <span class="comment-author">{{ $comment->user->name }}</span>
                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                <div class="comment-body">{{ Str::limit($comment->body, 100) }}</div>
              </div>
            </div>
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
          <p class="text-gray-500 flex items-center">
            <i class="fas fa-comment-slash mr-2"></i>
            まだコメントがありません
          </p>
        </div>
        @endif

        <!-- アクションボタン -->
        <div class="actions">
          <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">
            <i class="fas fa-eye mr-1"></i> 詳細を見る
          </a>
          @auth
          @if(auth()->id() === $post->user_id)
          <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-edit mr-1"></i> 編集
          </a>
          @endif
          @endauth
          <a href="{{ route('shops.show', $post->shop->id) }}" class="btn btn-outline-primary">
            <i class="fas fa-store mr-1"></i> 店舗詳細
          </a>
        </div>
      </div>
      @empty
      <!-- 投稿がない場合 -->
      <div class="text-center py-16">
        <i class="fas fa-inbox text-6xl text-gray-400 mb-6"></i>
        <h3 class="text-2xl text-gray-600 mb-4">まだ投稿がありません</h3>
        <p class="text-gray-500 mb-6">最初の投稿を作成して、お気に入りの店舗を共有しましょう！</p>
        @auth
        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-plus mr-2"></i> 投稿を作成
        </a>
        @else
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-user-plus mr-2"></i> 今すぐ登録
        </a>
        @endauth
      </div>
      @endforelse
    </div>

    <!-- ページネーション -->
    @if(method_exists($posts, 'links'))
    <div class="pagination-wrapper">
      {{ $posts->links() }}
    </div>
    @endif
  </div>
</x-app-layout>