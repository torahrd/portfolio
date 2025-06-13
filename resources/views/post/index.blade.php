@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-mocha-50 py-8">
  <div class="container mx-auto px-4">
    <!-- ヘッダー -->
    <header class="text-center mb-12">
      <h1 class="text-4xl font-bold text-neutral-900 mb-4">
        みんなの<span class="text-mocha-600">お店レビュー</span>
      </h1>
      <p class="text-neutral-600 text-lg">お気に入りのお店を共有しよう</p>
    </header>

    <!-- 投稿一覧 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="posts-container">
      @forelse($posts as $post)
      <article class="post-card group" data-post-id="{{ $post->id }}">
        <header class="post-header">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <img src="{{ $post->user->profile_photo_url ?? '/images/default-avatar.svg' }}"
                alt="{{ $post->user->name }}"
                class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
              <div>
                <h4 class="font-semibold text-neutral-900">{{ $post->user->name }}</h4>
                <time class="text-sm text-neutral-500">
                  {{ $post->created_at->diffForHumans() }}
                </time>
              </div>
            </div>
            <div class="post-status">
              <!-- 完全にPHP側で条件分岐処理 -->
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
          <!-- 店舗情報 -->
          <div class="post-shop-info">
            <h3 class="text-xl font-semibold text-neutral-900 mb-2">
              <a href="{{ route('shops.show', $post->shop->id) }}" class="post-shop-link">
                <i class="fas fa-store"></i>
                {{ $post->shop->name }}
              </a>
            </h3>

            @if($post->shop->address)
            <p class="text-sm text-neutral-600 mb-3 flex items-center gap-2">
              <i class="fas fa-map-marker-alt text-neutral-400"></i>
              {{ $post->shop->address }}
            </p>
            @endif
          </div>

          <!-- 投稿本文 -->
          @if($post->body)
          <div class="mb-4">
            <p class="text-neutral-700 leading-relaxed">
              {{ Str::limit($post->body, 200) }}
              @if(strlen($post->body) > 200)
              <a href="{{ route('posts.show', $post->id) }}"
                class="text-mocha-600 hover:text-mocha-700 font-medium ml-1">
                続きを読む
              </a>
              @endif
            </p>
          </div>
          @endif

          <!-- 予算情報 -->
          @if($post->budget)
          <div class="mb-4">
            <span class="post-budget">
              <i class="fas fa-yen-sign"></i>
              予算: {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
            </span>
          </div>
          @endif

          <!-- メニュー情報 -->
          @if($post->menus)
          <div class="mb-4 p-4 bg-gradient-to-r from-sage-50 to-sage-100 rounded-xl border border-sage-200">
            <h4 class="text-sm font-semibold text-sage-800 mb-2 flex items-center gap-2">
              <i class="fas fa-utensils text-sage-600"></i>
              おすすめメニュー
            </h4>
            <p class="text-sage-700 text-sm">{{ Str::limit($post->menus, 100) }}</p>
          </div>
          @endif

          <!-- 参考URL -->
          @if($post->reference_url)
          <div class="mb-4">
            <a href="{{ $post->reference_url }}"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 text-sm text-electric-600 hover:text-electric-700 font-medium">
              <i class="fas fa-external-link-alt"></i>
              詳細情報を見る
            </a>
          </div>
          @endif
        </div>

        <!-- アクションボタン -->
        <footer class="post-actions">
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
        </footer>
      </article>
      @empty
      <div class="col-span-full text-center py-12">
        <div class="text-6xl text-neutral-300 mb-4">🍽️</div>
        <h3 class="text-xl font-semibold text-neutral-700 mb-2">まだ投稿がありません</h3>
        <p class="text-neutral-500 mb-6">最初の投稿をしてみませんか？</p>
        @auth
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
          <i class="fas fa-plus mr-2"></i>新しい投稿を作成
        </a>
        @endauth
      </div>
      @endforelse
    </div>

    <!-- ページネーション -->
    @if($posts->hasPages())
    <div class="mt-12 flex justify-center">
      {{ $posts->links() }}
    </div>
    @endif
  </div>
</div>

<!-- JavaScript初期化 -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const postsContainer = document.getElementById('posts-container');
    if (postsContainer) {
      initializePostCards();
    }
  });

  function initializePostCards() {
    const postCards = document.querySelectorAll('.post-card');

    postCards.forEach(card => {
      const postId = card.getAttribute('data-post-id');

      // ホバーエフェクト
      card.addEventListener('mouseenter', function() {
        this.classList.add('card-hover');
      });

      card.addEventListener('mouseleave', function() {
        this.classList.remove('card-hover');
      });

      console.log(`投稿ID ${postId} の初期化完了`);
    });
  }
</script>
@endsection