@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-mocha-50 py-8">
  <div class="container mx-auto px-4 max-w-4xl">
    <article class="post-detail-card"
      data-post-id="{{ $post->id }}"
      data-post-user-id="{{ $post->user_id }}"
      data-is-owner="{{ auth()->check() && auth()->id() === $post->user_id ? 'true' : 'false' }}">

      <!-- 投稿ヘッダー -->
      <header class="post-detail-header mb-8">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-4">
            <img src="{{ $post->user->profile_photo_url ?? '/images/default-avatar.svg' }}"
              alt="{{ $post->user->name }}"
              class="w-12 h-12 rounded-full border-2 border-white shadow-lg">
            <div>
              <h2 class="text-lg font-bold text-neutral-900">{{ $post->user->name }}</h2>
              <time class="text-sm text-neutral-500">
                {{ $post->created_at->format('Y年n月j日 H:i') }}
              </time>
            </div>
          </div>
          <div class="post-status">
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

        <!-- 店舗情報 -->
        <div class="shop-info-card">
          <h1 class="text-3xl font-bold text-neutral-900 mb-3 flex items-center gap-3">
            <i class="fas fa-store text-mocha-600"></i>
            {{ $post->shop->name }}
          </h1>

          @if($post->shop->address)
          <p class="text-neutral-600 mb-4 flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-neutral-400"></i>
            {{ $post->shop->address }}
          </p>
          @endif
        </div>
      </header>

      <!-- 投稿本文 -->
      @if($post->body)
      <section class="post-body mb-8">
        <div class="prose max-w-none">
          <p class="text-neutral-700 text-lg leading-relaxed whitespace-pre-wrap">{{ $post->body }}</p>
        </div>
      </section>
      @endif

      <!-- 詳細情報 -->
      <div class="post-details grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- 予算情報 -->
        @if($post->budget)
        <div class="detail-card">
          <h3 class="detail-title">
            <i class="fas fa-yen-sign text-electric-600"></i>
            予算（一人当たり）
          </h3>
          <p class="detail-value">{{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}</p>
        </div>
        @endif

        <!-- メニュー情報 -->
        @if($post->menus)
        <div class="detail-card">
          <h3 class="detail-title">
            <i class="fas fa-utensils text-sage-600"></i>
            おすすめメニュー
          </h3>
          <p class="detail-value">{{ $post->menus }}</p>
        </div>
        @endif
      </div>

      <!-- 参考URL -->
      @if($post->reference_url)
      <section class="mb-8">
        <a href="{{ $post->reference_url }}"
          target="_blank"
          rel="noopener noreferrer"
          class="reference-link">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-coral-400 to-coral-600 flex items-center justify-center text-white flex-shrink-0">
            <i class="fas fa-external-link-alt"></i>
          </div>
          <div class="flex-1">
            <div class="font-semibold text-neutral-900">詳細情報</div>
            <div class="text-sm text-neutral-600">{{ $post->reference_url }}</div>
          </div>
          <i class="fas fa-arrow-right text-neutral-400"></i>
        </a>
      </section>
      @endif

      <!-- アクション・統計 -->
      <div class="flex items-center justify-between pt-6 border-t border-neutral-200">
        <div class="flex items-center gap-6">
          <!-- いいねボタン -->
          <button class="like-button flex items-center gap-2 text-neutral-600 hover:text-coral-600 transition-colors duration-200"
            data-post-id="{{ $post->id }}"
            data-liked="false">
            <i class="far fa-heart text-lg"></i>
            <span class="like-count">0</span>
          </button>

          <!-- コメントボタン -->
          <a href="#comments"
            class="flex items-center gap-2 text-neutral-600 hover:text-electric-600 transition-colors duration-200">
            <i class="far fa-comment text-lg"></i>
            <span>0</span>
          </a>

          <!-- シェアボタン -->
          <button class="share-button flex items-center gap-2 text-neutral-600 hover:text-sage-600 transition-colors duration-200"
            data-url="{{ route('posts.show', $post->id) }}"
            data-title="{{ $post->shop->name }}">
            <i class="far fa-share text-lg"></i>
            <span>シェア</span>
          </button>
        </div>

        <div class="text-sm text-neutral-500">
          <time datetime="{{ $post->created_at->format('Y-m-d') }}">
            {{ $post->created_at->format('Y年n月j日') }}
          </time>
        </div>
      </div>

      <!-- 編集・削除ボタン（オーナーのみ） -->
      @auth
      @if(auth()->id() === $post->user_id)
      <div class="flex items-center gap-4 pt-6 border-t border-neutral-200 mt-6">
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-secondary">
          <i class="fas fa-edit mr-2"></i>編集
        </a>
        <form method="POST" action="{{ route('posts.destroy', $post->id) }}" class="inline">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="btn btn-outline-danger"
            onclick="return confirm('この投稿を削除してもよろしいですか？')">
            <i class="fas fa-trash mr-2"></i>削除
          </button>
        </form>
      </div>
      @endif
      @endauth
    </article>
  </div>
</div>

<!-- ★修正: JavaScript初期化（@json使用せず） -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    initializePostShow();
  });

  function initializePostShow() {
    const postArticle = document.querySelector('.post-detail-card');
    if (!postArticle) return;

    // データ属性から値を取得
    const postData = {
      id: postArticle.getAttribute('data-post-id'),
      userId: postArticle.getAttribute('data-post-user-id'),
      isOwner: postArticle.getAttribute('data-is-owner') === 'true'
    };

    // いいねボタンの初期化
    initializeLikeButton(postData);

    // シェアボタンの初期化
    initializeShareButton();
  }

  function initializeLikeButton(postData) {
    const likeButton = document.querySelector('.like-button');
    if (!likeButton) return;

    likeButton.addEventListener('click', function() {
      if (!window.AppConfig.isAuthenticated) {
        alert('いいねするにはログインが必要です。');
        return;
      }

      toggleLike(postData.id);
    });
  }

  function initializeShareButton() {
    const shareButton = document.querySelector('.share-button');
    if (!shareButton) return;

    shareButton.addEventListener('click', function() {
      const url = this.getAttribute('data-url');
      const title = this.getAttribute('data-title');
      sharePost(url, title);
    });
  }

  function toggleLike(postId) {
    // いいね機能の実装
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window.AppConfig.csrfToken,
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          updateLikeButton(data.liked, data.likes_count);
        }
      })
      .catch(error => {
        console.error('いいねエラー:', error);
      });
  }

  function updateLikeButton(liked, count) {
    const button = document.querySelector('.like-button');
    const icon = button.querySelector('i');
    const countSpan = button.querySelector('.like-count');

    if (liked) {
      icon.className = 'fas fa-heart text-lg text-coral-600';
      button.classList.add('liked');
    } else {
      icon.className = 'far fa-heart text-lg';
      button.classList.remove('liked');
    }

    countSpan.textContent = count;
  }

  function sharePost(url, title) {
    if (navigator.share) {
      navigator.share({
        title: title,
        url: url
      });
    } else {
      navigator.clipboard.writeText(url).then(() => {
        alert('URLをクリップボードにコピーしました');
      });
    }
  }
</script>
@endsection