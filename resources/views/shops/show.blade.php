@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-50">
  <!-- ヒーローイメージセクション -->
  <div class="relative h-64 md:h-96 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent z-10"></div>

    <!-- 背景画像（デフォルトのグラデーション背景） -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-600"></div>

    <!-- ヒーローコンテンツ -->
    <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ $shop->name }}</h1>
        <div class="flex items-center space-x-4 mb-2">
          @if($shop->today_business_hours)
          <x-atoms.badge :variant="$shop->is_open_now ? 'success' : 'error'" size="sm">
            {{ $shop->is_open_now ? '営業中' : '営業時間外' }}
          </x-atoms.badge>
          @else
          <x-atoms.badge variant="warning" size="sm">営業時間不明</x-atoms.badge>
          @endif

          @if($shop->category ?? false)
          <span class="text-white/80 text-sm">{{ $shop->category }}</span>
          @endif
        </div>

        @if($shop->address)
        <div class="flex items-center text-white/90 text-sm">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          {{ $shop->address }}
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- アクションバー（スティッキー） -->
  <div class="bg-white border-b border-neutral-200 sticky top-16 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-6">
          <!-- お気に入りボタン（ハート形） -->
          @auth
          <button id="favorite-btn"
            class="flex items-center space-x-2 favorite-button transition-all duration-200 hover:scale-105"
            data-shop-id="{{ $shop->id }}"
            data-favorited="{{ $isFavorited ? 'true' : 'false' }}">
            <div class="relative">
              <svg class="w-7 h-7 {{ $isFavorited ? 'text-red-500 fill-current' : 'text-neutral-400' }} transition-colors duration-200"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
            </div>
            <div>
              <span class="text-sm font-medium text-neutral-700 favorite-count">{{ $shop->favorites_count }}</span>
              <span class="text-xs text-neutral-500 block">お気に入り</span>
            </div>
          </button>
          @else
          <a href="{{ route('login') }}"
            class="flex items-center space-x-2 transition-all duration-200 hover:scale-105">
            <div class="relative">
              <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
            </div>
            <div>
              <span class="text-sm font-medium text-neutral-700">{{ $shop->favorites_count }}</span>
              <span class="text-xs text-neutral-500 block">お気に入り</span>
            </div>
          </a>
          @endauth

          <!-- 平均予算 -->
          @if($shop->average_budget)
          <div class="text-sm">
            <span class="text-neutral-600">平均予算</span>
            <span class="font-semibold ml-1 text-primary-600">¥{{ number_format($shop->average_budget) }}</span>
          </div>
          @endif
        </div>

        <!-- 共有ボタン -->
        <button class="flex items-center space-x-2 text-neutral-600 hover:text-neutral-900 transition-colors duration-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
          </svg>
          <span class="text-sm">共有</span>
        </button>
      </div>
    </div>
  </div>

  <!-- メインコンテンツ -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- タブナビゲーション -->
    <div x-data="{ activeTab: 'info' }" class="mb-8">
      <div class="border-b border-neutral-200">
        <nav class="-mb-px flex space-x-8">
          <button @click="activeTab = 'info'"
            :class="activeTab === 'info' ? 'border-primary-500 text-primary-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
            class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            基本情報
          </button>
          <button @click="activeTab = 'posts'"
            :class="activeTab === 'posts' ? 'border-primary-500 text-primary-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
            class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            投稿 ({{ $shop->recent_posts->count() }})
          </button>
          <button @click="activeTab = 'photos'"
            :class="activeTab === 'photos' ? 'border-primary-500 text-primary-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300'"
            class="py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
            写真
          </button>
        </nav>
      </div>

      <!-- タブコンテンツ -->
      <div class="mt-6">
        <!-- 基本情報タブ -->
        <div x-show="activeTab === 'info'" x-transition>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 店舗情報カード -->
            <x-molecules.shop-info-card :shop="$shop" :show-actions="false" />

            <!-- 営業時間カード -->
            <x-molecules.business-hours-card :business_hours="$shop->business_hours" />
          </div>
        </div>

        <!-- 投稿タブ -->
        <div x-show="activeTab === 'posts'" x-transition>
          @if($shop->recent_posts->count() > 0)
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($shop->recent_posts as $post)
            <div class="bg-white rounded-xl shadow-card p-6 hover:shadow-card-hover transition-shadow duration-300">
              <div class="mb-4">
                <h4 class="text-lg font-semibold text-neutral-900 mb-2">
                  <a href="{{ route('posts.show', $post) }}" class="hover:text-primary-500 transition-colors duration-200">
                    {{ $post->user->name }}さんの投稿
                  </a>
                </h4>
                <p class="text-sm text-neutral-600">{{ $post->created_at->format('Y年m月d日') }}</p>
              </div>

              @if($post->repeat_menu)
              <p class="text-sm text-neutral-700 mb-2">
                <strong>リピートメニュー:</strong> {{ $post->repeat_menu }}
              </p>
              @endif

              @if($post->memo)
              <p class="text-sm text-neutral-600">
                {{ Str::limit($post->memo, 100) }}
              </p>
              @endif

              <div class="mt-4">
                <a href="{{ route('posts.show', $post) }}"
                  class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                  詳細を見る →
                </a>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7"></path>
            </svg>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">まだ投稿がありません</h3>
            <p class="text-neutral-600 mb-6">この店舗への最初の投稿をしてみませんか？</p>
            @auth
            {{-- ★修正：修正済みx-atoms.buttonコンポーネントを使用 --}}
            <x-atoms.button variant="primary" href="{{ route('posts.create') }}">
              投稿を作成
            </x-atoms.button>
            @else
            {{-- ★修正：修正済みx-atoms.buttonコンポーネントを使用 --}}
            <x-atoms.button variant="primary" href="{{ route('login') }}">
              ログインして投稿
            </x-atoms.button>
            @endauth
          </div>
          @endif
        </div>

        <!-- 写真タブ -->
        <div x-show="activeTab === 'photos'" x-transition>
          <x-molecules.shop-gallery :posts="$shop->recent_posts" />
        </div>
      </div>
    </div>
  </div>

  <!-- フローティング予約ボタン -->
  @if($shop->reservation_url ?? false)
  <div class="fixed bottom-20 md:bottom-8 right-4 z-40">
    <a href="{{ $shop->reservation_url }}"
      target="_blank"
      rel="noopener noreferrer"
      class="bg-primary-500 text-white px-6 py-3 rounded-full shadow-lg hover:bg-primary-600 hover:shadow-xl transition-all duration-200 flex items-center space-x-2 hover:scale-105">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
      </svg>
      <span class="font-medium">予約する</span>
    </a>
  </div>
  @endif
</div>

<!-- JavaScript -->
@verbatim
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // CSRFトークンの設定
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // お気に入りボタンの処理
    const favoriteBtn = document.getElementById('favorite-btn');
    if (favoriteBtn) {
      favoriteBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const shopId = this.dataset.shopId;
        const isFavorited = this.dataset.favorited === 'true';
        const heartIcon = this.querySelector('svg');
        const countElement = this.querySelector('.favorite-count');

        // アニメーション開始
        heartIcon.classList.add('scale-125');
        setTimeout(() => heartIcon.classList.remove('scale-125'), 300);

        // AJAX リクエスト
        fetch(`/shops/${shopId}/favorite`, {
            method: isFavorited ? 'DELETE' : 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // 状態を更新
              this.dataset.favorited = data.is_favorited;

              // ハートの色を変更
              if (data.is_favorited) {
                heartIcon.classList.add('text-red-500', 'fill-current');
                heartIcon.classList.remove('text-neutral-400');
              } else {
                heartIcon.classList.remove('text-red-500', 'fill-current');
                heartIcon.classList.add('text-neutral-400');
              }

              // カウントを更新
              countElement.textContent = data.favorite_count;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            // エラーアニメーション
            this.classList.add('animate-pulse');
            setTimeout(() => this.classList.remove('animate-pulse'), 1000);
          });
      });
    }
  });
</script>
@endverbatim
@endsection