@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-mocha-50 py-8">
  <div class="container mx-auto px-4 max-w-6xl">
    <!-- プロフィールヘッダー -->
    <div class="profile-header-card mb-8"
      data-profile-user-id="{{ $user->id }}"
      data-is-own-profile="{{ auth()->check() && auth()->id() === $user->id ? 'true' : 'false' }}">

      <div class="flex flex-col md:flex-row items-center gap-8">
        <!-- プロフィール画像 -->
        <div class="flex-shrink-0">
          <img src="{{ $user->profile_photo_url ?? '/images/default-avatar.svg' }}"
            alt="{{ $user->name }}"
            class="w-32 h-32 rounded-full border-4 border-white shadow-xl">
        </div>

        <!-- プロフィール情報 -->
        <div class="flex-1 text-center md:text-left">
          <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ $user->name }}</h1>

          @if($user->email)
          <p class="text-neutral-600 mb-4">{{ $user->email }}</p>
          @endif

          <!-- 統計情報 -->
          <div class="flex items-center justify-center md:justify-start gap-8 mb-6">
            <div class="text-center">
              <div class="text-2xl font-bold text-mocha-600">{{ $posts->total() }}</div>
              <div class="text-sm text-neutral-500">投稿</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-mocha-600">0</div>
              <div class="text-sm text-neutral-500">フォロワー</div>
            </div>
            <div class="text-center">
              <div class="text-2xl font-bold text-mocha-600">0</div>
              <div class="text-sm text-neutral-500">フォロー</div>
            </div>
          </div>

          <!-- アクションボタン -->
          <div class="flex items-center gap-4">
            @auth
            @if(auth()->id() === $user->id)
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
              <i class="fas fa-edit mr-2"></i>プロフィール編集
            </a>
            @else
            <button class="btn btn-outline-primary">
              <i class="fas fa-user-plus mr-2"></i>フォロー
            </button>
            @endif
            @endauth
          </div>
        </div>
      </div>
    </div>

    <!-- 投稿一覧 -->
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-neutral-900 mb-6">投稿一覧</h2>

      @if($posts->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
        <article class="post-card" data-post-id="{{ $post->id }}">
          <header class="post-header">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <img src="{{ $post->user->profile_photo_url ?? '/images/default-avatar.svg' }}"
                  alt="{{ $post->user->name }}"
                  class="w-8 h-8 rounded-full">
                <div>
                  <h4 class="font-medium text-neutral-900">{{ $post->user->name }}</h4>
                  <time class="text-xs text-neutral-500">
                    {{ $post->created_at->diffForHumans() }}
                  </time>
                </div>
              </div>
              <div class="post-status">
                <!-- ★修正: 完全にPHP側で条件分岐 -->
                @if($post->visit_status)
                <span class="status-badge status-visited">
                  <i class="fas fa-check-circle"></i>
                  訪問済み
                </span>
                @else
                <span class="status-badge status-planned">
                  <i class="fas fa-heart"></i>
                  行きたい
                </span>
                @endif
              </div>
            </div>
          </header>

          <!-- 投稿内容 -->
          <div class="post-content">
            <h3 class="text-xl font-semibold mb-3">
              <a href="{{ route('shops.show', $post->shop->id) }}" class="post-shop-link">
                <i class="fas fa-store mr-2"></i>
                {{ $post->shop->name }}
              </a>
            </h3>

            @if($post->body)
            <p class="text-neutral-700 leading-relaxed mb-4">
              {{ Str::limit($post->body, 150) }}
            </p>
            @endif

            @if($post->budget)
            <div class="mb-4">
              <span class="post-budget">
                <i class="fas fa-yen-sign"></i>
                予算: {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
              </span>
            </div>
            @endif
          </div>

          <!-- アクション -->
          <div class="post-actions">
            <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">
              <i class="fas fa-eye mr-1"></i>詳細を見る
            </a>
            @auth
            @if(auth()->id() === $post->user_id)
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-secondary">
              <i class="fas fa-edit mr-1"></i>編集
            </a>
            @endif
            @endauth
          </div>
        </article>
        @endforeach
      </div>

      <!-- ページネーション -->
      @if($posts->hasPages())
      <div class="mt-8 flex justify-center">
        {{ $posts->links() }}
      </div>
      @endif
      @else
      <!-- 投稿なしの状態 -->
      <div class="text-center py-12">
        <div class="text-6xl text-neutral-300 mb-4">📝</div>
        <h3 class="text-xl font-semibold text-neutral-700 mb-2">まだ投稿がありません</h3>
        <p class="text-neutral-500 mb-6">最初の投稿を作成してみませんか？</p>
        @if(auth()->check() && auth()->id() === $user->id)
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
          <i class="fas fa-plus mr-2"></i>新しい投稿を作成
        </a>
        @endif
      </div>
      @endif
    </div>
  </div>
</div>

<!-- ★修正: プロフィール初期化（@json使用せず） -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    initializeProfile();
  });

  function initializeProfile() {
    const profileHeader = document.querySelector('.profile-header-card');
    if (!profileHeader) return;

    // プロフィールデータをdata属性から取得
    const profileData = {
      userId: profileHeader.getAttribute('data-profile-user-id'),
      isOwnProfile: profileHeader.getAttribute('data-is-own-profile') === 'true'
    };

    console.log('プロフィール初期化:', profileData);

    // フォローボタンの初期化（該当する場合）
    initializeFollowButton(profileData);

    // 投稿カードの初期化
    initializeProfilePostCards();
  }

  function initializeFollowButton(profileData) {
    // フォローボタンがある場合の処理
    const followButton = document.querySelector('.btn-outline-primary');
    if (followButton && !profileData.isOwnProfile) {
      followButton.addEventListener('click', function() {
        // フォロー機能の実装（今後追加）
        console.log('フォロー機能: 未実装');
      });
    }
  }

  function initializeProfilePostCards() {
    const postCards = document.querySelectorAll('.post-card');

    postCards.forEach(card => {
      // ホバーエフェクト
      card.addEventListener('mouseenter', function() {
        this.classList.add('card-hover');
      });

      card.addEventListener('mouseleave', function() {
        this.classList.remove('card-hover');
      });
    });
  }
</script>
@endsection