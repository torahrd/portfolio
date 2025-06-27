<x-app-layout>
  <div class="min-h-screen bg-neutral-50">
    <!-- ヒーローイメージセクション -->
    <div class="relative h-64 md:h-96 overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent z-10"></div>

      <!-- 背景画像（グラデーション背景） -->
      <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-600"></div>

      <!-- ヒーローコンテンツ -->
      <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
        <div class="max-w-7xl mx-auto">
          <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ $shop->name }}</h1>
          <div class="flex items-center space-x-4 mb-2">
            @if($shop->today_business_hours)
            <x-atoms.badge-success size="sm">
              {{ $shop->is_open_now ? '営業中' : '営業時間外' }}
            </x-atoms.badge-success>
            @else
            <x-atoms.badge-warning size="sm">営業時間不明</x-atoms.badge-warning>
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

    <!-- アクションバー（星形ボタン対応） -->
    <div class="bg-white border-b border-neutral-200 sticky top-16 z-30 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-6">
            <!-- お気に入りボタン（星形のまま） -->
            @auth
            <button id="favorite-btn"
              class="flex items-center space-x-2 favorite-button transition-all duration-200 hover:scale-105 {{ $isFavorited ? 'favorited' : 'not-favorited' }}"
              data-shop-id="{{ $shop->id }}"
              data-favorited="{{ $isFavorited ? 'true' : 'false' }}">
              <div class="relative">
                <span class="favorite-star text-2xl">{{ $isFavorited ? '★' : '☆' }}</span>
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
                <span class="text-2xl text-neutral-400">☆</span>
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
              <h3 class="text-lg font-medium text-neutral-900 mb-2">まだ投稿がありません</h3>
              <p class="text-neutral-600 mb-6">この店舗への最初の投稿をしてみませんか？</p>
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
  </div>

  <!-- JavaScript（星形ボタン対応） -->
  @push('scripts')
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
          const starElement = this.querySelector('.favorite-star');
          const countElement = this.querySelector('.favorite-count');

          // アニメーション開始
          starElement.style.transform = 'scale(1.3)';
          setTimeout(() => starElement.style.transform = 'scale(1)', 300);

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

                // 星の表示を変更
                starElement.textContent = data.is_favorited ? '★' : '☆';

                // クラスを更新
                if (data.is_favorited) {
                  this.classList.remove('not-favorited');
                  this.classList.add('favorited');
                } else {
                  this.classList.remove('favorited');
                  this.classList.add('not-favorited');
                }

                // カウントを更新
                countElement.textContent = data.favorites_count;
              }
            })
            .catch(error => {
              console.error('Error:', error);
              // エラーアニメーション
              this.style.animation = 'pulse 1s';
              setTimeout(() => this.style.animation = '', 1000);
            });
        });
      }
    });
  </script>
  @endverbatim
  @endpush
</x-app-layout>