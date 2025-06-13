<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-2xl text-neutral-800 leading-tight flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white">
          <i class="fas fa-home text-lg"></i>
        </div>
        投稿フィード
      </h2>

      @auth
      <a href="{{ route('posts.create') }}"
        class="btn btn-primary hover-lift">
        <i class="fas fa-plus mr-2"></i>
        新しい投稿
      </a>
      @endauth
    </div>
  </x-slot>

  <div class="max-w-4xl mx-auto">
    <!-- フィルター・検索セクション -->
    <div class="post-filters mb-8 animate-slide-down">
      <div class="flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
          <i class="fas fa-filter text-neutral-500"></i>
          <span class="text-sm font-medium text-neutral-700">フィルター:</span>
        </div>

        <div class="filter-chip active" data-filter="all">
          <i class="fas fa-th-large"></i>
          すべて
        </div>

        <div class="filter-chip" data-filter="visited">
          <i class="fas fa-check-circle"></i>
          訪問済み
        </div>

        <div class="filter-chip" data-filter="planned">
          <i class="fas fa-heart"></i>
          行きたい
        </div>

        <div class="filter-chip" data-filter="recent">
          <i class="fas fa-clock"></i>
          最新
        </div>

        <!-- 検索フォーム -->
        <div class="flex-1 max-w-md ml-auto">
          <div class="relative">
            <input type="text"
              placeholder="店舗名・場所で検索..."
              class="form-input-base pl-10 pr-4 py-2 text-sm"
              id="search-input">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <i class="fas fa-search text-neutral-400"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    @auth
    <!-- 投稿作成プロンプト -->
    <div class="create-post-section animate-fade-in">
      <div class="text-center">
        <i class="fas fa-utensils text-4xl text-white/70 mb-4"></i>
        <h2 class="text-2xl font-light mb-4">
          新しい美食体験を共有しませんか？
        </h2>
        <p class="text-white/80 mb-6 max-w-2xl mx-auto">
          あなたのお気に入りの店舗や、今日の素敵な発見を<br>
          コミュニティのみんなと共有しましょう。
        </p>
        <a href="{{ route('posts.create') }}"
          class="btn glass-btn text-white hover:text-mocha-100 font-semibold">
          <i class="fas fa-plus mr-2"></i>
          投稿を作成する
        </a>
      </div>
    </div>
    @endauth

    <!-- 投稿一覧 -->
    <div class="space-y-6" id="posts-container">
      @forelse($posts as $index => $post)
      <article class="post-item"
        style="animation-delay: {{ $index * 0.1 }}s"
        data-visited="{{ $post->visit_status ? 'true' : 'false' }}"
        data-created="{{ $post->created_at->format('Y-m-d') }}">

        <!-- 投稿ヘッダー -->
        <header class="post-header">
          <a href="{{ route('profile.show', $post->user) }}"
            class="flex items-center hover-lift">
            <img src="{{ $post->user->avatar_url }}"
              alt="{{ $post->user->name }}"
              class="post-avatar">
            <div class="post-author-info">
              <div class="post-author">{{ $post->user->name }}</div>
              <div class="post-meta">
                <span>
                  <i class="fas fa-clock"></i>
                  <time datetime="{{ $post->created_at->toISOString() }}">
                    {{ $post->created_at->diffForHumans() }}
                  </time>
                </span>

                @if($post->visit_time)
                <span>
                  <i class="fas fa-calendar-alt"></i>
                  訪問: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y/m/d') }}
                </span>
                @endif

                <span class="visit-status-badge {{ $post->visit_status ? 'visit-status-visited' : 'visit-status-planned' }}">
                  <i class="fas fa-{{ $post->visit_status ? 'check-circle' : 'heart' }}"></i>
                  {{ $post->visit_status ? '訪問済み' : '行きたい' }}
                </span>
              </div>
            </div>
          </a>
        </header>

        <!-- 投稿内容 -->
        <div class="post-content">
          <!-- 店舗情報 -->
          <div class="post-shop-info">
            <h3 class="text-xl font-semibold text-neutral-900 mb-2">
              <a href="{{ route('shops.show', $post->shop->id) }}"
                class="post-shop-link">
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
              class="inline-flex items-center gap-2 text-electric-600 hover:text-electric-700 text-sm font-medium transition-colors duration-200">
              <i class="fas fa-external-link-alt"></i>
              詳細情報を見る
            </a>
          </div>
          @endif

          <!-- タグ -->
          @if($post->folders && $post->folders->count() > 0)
          <div class="post-tags">
            @foreach($post->folders as $folder)
            <span class="post-tag">
              <i class="fas fa-tag"></i>
              {{ $folder->name }}
            </span>
            @endforeach
          </div>
          @endif
        </div>

        <!-- 投稿統計・アクション -->
        <div class="post-stats">
          <button class="post-stat" onclick="toggleLike({{ $post->id }})">
            <i class="fas fa-heart"></i>
            <span id="like-count-{{ $post->id }}">12</span>
          </button>

          <a href="{{ route('posts.show', $post->id) }}#comments"
            class="post-stat">
            <i class="fas fa-comment"></i>
            <span>3</span>
          </a>

          <button class="post-stat" onclick="sharePost({{ $post->id }})">
            <i class="fas fa-share"></i>
            <span>共有</span>
          </button>
        </div>

        <!-- アクションボタン -->
        <div class="post-actions">
          <a href="{{ route('posts.show', $post->id) }}"
            class="btn btn-primary">
            <i class="fas fa-eye mr-1"></i>
            詳細を見る
          </a>

          @auth
          @if(auth()->id() === $post->user_id)
          <a href="{{ route('posts.edit', $post->id) }}"
            class="btn btn-outline-secondary">
            <i class="fas fa-edit mr-1"></i>
            編集
          </a>
          @endif
          @endauth
        </div>
      </article>
      @empty
      <!-- 空の状態 -->
      <div class="text-center py-16 animate-fade-in">
        <div class="glass-card p-12 max-w-md mx-auto">
          <div class="w-20 h-20 rounded-full bg-gradient-to-br from-neutral-200 to-neutral-300 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-inbox text-3xl text-neutral-400"></i>
          </div>
          <h3 class="text-xl font-semibold text-neutral-700 mb-4">
            まだ投稿がありません
          </h3>
          <p class="text-neutral-500 mb-6 leading-relaxed">
            まだ誰も投稿していないようです。<br>
            あなたが最初の投稿者になりませんか？
          </p>
          @auth
          <a href="{{ route('posts.create') }}"
            class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>
            最初の投稿を作成
          </a>
          @else
          <div class="space-y-3">
            <a href="{{ route('register') }}"
              class="btn btn-primary">
              <i class="fas fa-user-plus mr-2"></i>
              新規登録して投稿する
            </a>
            <div class="text-sm text-neutral-500">
              または
              <a href="{{ route('login') }}"
                class="text-mocha-600 hover:text-mocha-700 font-medium">
                ログイン
              </a>
            </div>
          </div>
          @endauth
        </div>
      </div>
      @endforelse
    </div>

    <!-- ページネーション -->
    @if($posts->hasPages())
    <div class="pagination-wrapper animate-fade-in">
      <div class="glass-card p-4">
        {{ $posts->links() }}
      </div>
    </div>
    @endif
  </div>

  <!-- JavaScript for interactive features -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // フィルター機能
      const filterChips = document.querySelectorAll('.filter-chip');
      const posts = document.querySelectorAll('.post-item');

      filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
          // アクティブ状態の更新
          filterChips.forEach(c => c.classList.remove('active'));
          this.classList.add('active');

          const filter = this.dataset.filter;

          posts.forEach((post, index) => {
            let show = true;

            switch (filter) {
              case 'visited':
                show = post.dataset.visited === 'true';
                break;
              case 'planned':
                show = post.dataset.visited === 'false';
                break;
              case 'recent':
                const postDate = new Date(post.dataset.created);
                const weekAgo = new Date();
                weekAgo.setDate(weekAgo.getDate() - 7);
                show = postDate > weekAgo;
                break;
              case 'all':
              default:
                show = true;
            }

            if (show) {
              post.style.display = 'block';
              post.style.animationDelay = (index * 0.1) + 's';
              post.classList.add('animate-slide-up');
            } else {
              post.style.display = 'none';
            }
          });
        });
      });

      // 検索機能
      const searchInput = document.getElementById('search-input');
      let searchTimeout;

      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.toLowerCase().trim();

        searchTimeout = setTimeout(() => {
          posts.forEach(post => {
            const shopName = post.querySelector('.post-shop-link').textContent.toLowerCase();
            const content = post.querySelector('.post-content').textContent.toLowerCase();

            if (query === '' || shopName.includes(query) || content.includes(query)) {
              post.style.display = 'block';
            } else {
              post.style.display = 'none';
            }
          });
        }, 300);
      });
    });

    // いいね機能（デモ用）
    function toggleLike(postId) {
      const likeCount = document.getElementById(`like-count-${postId}`);
      const currentCount = parseInt(likeCount.textContent);
      const button = event.currentTarget;
      const icon = button.querySelector('i');

      if (icon.classList.contains('fas')) {
        // いいねを外す
        icon.classList.remove('fas');
        icon.classList.add('far');
        likeCount.textContent = currentCount - 1;
        button.classList.remove('text-coral-600');
      } else {
        // いいねを付ける
        icon.classList.remove('far');
        icon.classList.add('fas');
        likeCount.textContent = currentCount + 1;
        button.classList.add('text-coral-600');

        // アニメーション効果
        icon.classList.add('animate-bounce-gentle');
        setTimeout(() => {
          icon.classList.remove('animate-bounce-gentle');
        }, 600);
      }
    }

    // 共有機能（デモ用）
    function sharePost(postId) {
      if (navigator.share) {
        navigator.share({
          title: 'FoodieConnect - おすすめ店舗',
          text: 'この素敵な店舗をチェックしてみてください！',
          url: window.location.origin + `/posts/${postId}`
        });
      } else {
        // フォールバック: URLをクリップボードにコピー
        const url = window.location.origin + `/posts/${postId}`;
        navigator.clipboard.writeText(url).then(() => {
          // トースト通知を表示
          showToast('URLをクリップボードにコピーしました', 'success');
        });
      }
    }

    // トースト通知（デモ用）
    function showToast(message, type = 'info') {
      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      toast.innerHTML = `
                <i class="fas fa-check mr-2"></i>
                ${message}
            `;
      document.body.appendChild(toast);

      setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => {
          document.body.removeChild(toast);
        }, 300);
      }, 3000);
    }

    // 無限スクロール（オプション）
    let loading = false;
    let currentPage = {
      {
        $posts - > currentPage()
      }
    };
    const lastPage = {
      {
        $posts - > lastPage()
      }
    };

    window.addEventListener('scroll', function() {
      if (loading || currentPage >= lastPage) return;

      const scrollPosition = window.innerHeight + window.scrollY;
      const documentHeight = document.documentElement.offsetHeight;

      if (scrollPosition >= documentHeight - 1000) {
        loading = true;
        loadMorePosts();
      }
    });

    function loadMorePosts() {
      // 実際の実装では、Ajaxで次のページを読み込む
      console.log('Loading more posts...');
      loading = false;
    }
  </script>
</x-app-layout>