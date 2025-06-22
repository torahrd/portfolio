<x-app-layout>
  <div class="min-h-screen bg-neutral-50">
    <!-- メインコンテンツ -->
    <main class="container mx-auto px-4 py-6">
      <!-- 投稿作成セクション（未ログイン時） -->
      @guest
      <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-6 mb-8 text-white">
        <div class="text-center">
          <h2 class="text-2xl font-bold mb-2">ようこそ！</h2>
          <p class="mb-4 opacity-90">お気に入りの店舗を仲間と共有しませんか？</p>
          <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <x-atoms.button-secondary href="{{ route('login') }}">
              ログイン
            </x-atoms.button-secondary>
            <x-atoms.button-secondary href="{{ route('register') }}" class="text-white border-white hover:bg-white/10">
              新規登録
            </x-atoms.button-secondary>
          </div>
        </div>
      </div>
      @endguest

      <!-- タブナビゲーション -->
      <div class="mb-6">
        <x-molecules.tab-navigation
          :tabs="[
                        ['key' => 'popular', 'label' => '人気', 'active' => true],
                        ['key' => 'recent', 'label' => '新着', 'active' => false]
                    ]"
          active-tab="popular" />
      </div>

      <!-- 投稿グリッド -->
      <div id="posts-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @forelse($posts as $post)
        <x-molecules.post-card :post="$post" />
        @empty
        <!-- 空の状態 -->
        <div class="col-span-full">
          <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.712-3.714M14 40v-4a9.971 9.971 0 01.712-3.714M28 16a4 4 0 11-8 0 4 4 0 018 0zm-4 8a6 6 0 00-6 6v2m12-8a6 6 0 00-6 6v2m0 0V20a6 6 0 00-6 6v2m0 0V20a6 6 0 006-6v2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-neutral-900">投稿がありません</h3>
            <p class="mt-1 text-sm text-neutral-500">最初の投稿を作成してみましょう！</p>
            @auth
            <div class="mt-6">
              <x-atoms.button-primary href="{{ route('posts.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                投稿を作成
              </x-atoms.button-primary>
            </div>
            @endauth
          </div>
        </div>
        @endforelse
      </div>

      <!-- ページネーション -->
      @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator && $posts->hasPages())
      <div class="mt-8">
        {{ $posts->links('pagination::tailwind') }}
      </div>
      @endif

      <!-- インフィニットスクロール用ローディング -->
      <div id="loading-trigger" class="text-center py-8 opacity-0 transition-opacity duration-300">
        <div class="inline-flex items-center space-x-2">
          <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-500"></div>
          <span class="text-sm text-neutral-500">読み込み中...</span>
        </div>
      </div>
    </main>
  </div>

  <!-- モバイル用下部ナビゲーション -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-neutral-200 z-50">
    <div class="grid grid-cols-4 h-16">
      <!-- ホーム -->
      <a href="{{ route('posts.index') }}" class="flex flex-col items-center justify-center space-y-1 text-primary-500">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
        </svg>
        <span class="text-xs font-medium">ホーム</span>
      </a>

      <!-- 検索 -->
      <button class="flex flex-col items-center justify-center space-y-1 text-neutral-500 hover:text-neutral-700 transition-colors duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <span class="text-xs font-medium">検索</span>
      </button>

      <!-- 投稿作成 -->
      @auth
      <a href="{{ route('posts.create') }}" class="flex flex-col items-center justify-center space-y-1 text-neutral-500 hover:text-neutral-700 transition-colors duration-200">
        <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
        </div>
        <span class="text-xs font-medium">投稿</span>
      </a>
      @else
      <a href="{{ route('login') }}" class="flex flex-col items-center justify-center space-y-1 text-neutral-500 hover:text-neutral-700 transition-colors duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span class="text-xs font-medium">投稿</span>
      </a>
      @endauth

      <!-- マイページ -->
      @auth
      <a href="{{ route('profile.show', auth()->user()) }}" class="flex flex-col items-center justify-center space-y-1 text-neutral-500 hover:text-neutral-700 transition-colors duration-200">
        <div class="w-6 h-6 rounded-full overflow-hidden ring-2 ring-neutral-200">
          <img
            src="{{ auth()->user()->avatar_url ?? '/images/default-avatar.png' }}"
            alt="{{ auth()->user()->name }}"
            class="w-full h-full object-cover">
        </div>
        <span class="text-xs font-medium">マイページ</span>
      </a>
      @else
      <a href="{{ route('login') }}" class="flex flex-col items-center justify-center space-y-1 text-neutral-500 hover:text-neutral-700 transition-colors duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <span class="text-xs font-medium">プロフィール</span>
      </a>
      @endauth
    </div>
  </nav>

  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // タブ切り替えイベントリスナー
      document.addEventListener('tabChanged', function(event) {
        const activeTab = event.detail.activeTab;
        console.log('Active tab changed to:', activeTab);

        // ここでAjaxリクエストを送信して投稿を動的に読み込み
        // loadPosts(activeTab);
      });

      // インフィニットスクロール
      const loadingTrigger = document.getElementById('loading-trigger');
      const postsContainer = document.getElementById('posts-container');

      if (loadingTrigger && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              // ローディング表示
              loadingTrigger.classList.remove('opacity-0');

              // ここで次のページのデータを読み込み
              // loadMorePosts();

              setTimeout(() => {
                loadingTrigger.classList.add('opacity-0');
              }, 1000);
            }
          });
        }, {
          rootMargin: '100px'
        });

        observer.observe(loadingTrigger);
      }

      // いいねボタンのアニメーション
      document.addEventListener('click', function(event) {
        if (event.target.closest('.group\\/like')) {
          const button = event.target.closest('.group\\/like');
          const icon = button.querySelector('svg');

          // アニメーション効果
          icon.classList.add('animate-bounce-subtle');
          setTimeout(() => {
            icon.classList.remove('animate-bounce-subtle');
          }, 600);
        }
      });

      // 検索機能
      const searchInputs = document.querySelectorAll('input[type="search"]');
      searchInputs.forEach(input => {
        let searchTimeout;

        input.addEventListener('input', function() {
          clearTimeout(searchTimeout);
          const query = this.value.trim();

          searchTimeout = setTimeout(() => {
            if (query.length >= 2) {
              console.log('Searching for:', query);
              // ここで検索APIを呼び出し
              // performSearch(query);
            }
          }, 300);
        });
      });
    });

    // 投稿読み込み関数（実装例）
    function loadPosts(tab) {
      const container = document.getElementById('posts-container');

      // ローディング状態表示
      container.style.opacity = '0.5';

      // Ajax リクエスト（実装時に追加）
      fetch(`/posts?tab=${tab}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        })
        .then(response => response.json())
        .then(data => {
          // 投稿データを更新
          container.innerHTML = data.html;
          container.style.opacity = '1';
        })
        .catch(error => {
          console.error('Error loading posts:', error);
          container.style.opacity = '1';
        });
    }
  </script>
</x-app-layout>