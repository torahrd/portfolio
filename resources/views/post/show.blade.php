<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-2xl text-neutral-800 leading-tight flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white">
          <i class="fas fa-eye text-lg"></i>
        </div>
        投稿詳細
      </h2>

      <div class="flex items-center gap-3">
        @auth
        @if(auth()->id() === $post->user_id)
        <a href="{{ route('posts.edit', $post->id) }}"
          class="btn btn-outline-secondary hover-lift">
          <i class="fas fa-edit mr-2"></i>
          編集
        </a>
        @endif
        @endauth

        <button onclick="sharePost()"
          class="btn btn-outline-primary hover-lift">
          <i class="fas fa-share mr-2"></i>
          共有
        </button>
      </div>
    </div>
  </x-slot>

  <div class="max-w-4xl mx-auto">
    <!-- 戻るボタン -->
    <div class="mb-6 animate-slide-down">
      <a href="{{ url()->previous() }}"
        class="btn btn-outline-secondary hover-lift">
        <i class="fas fa-arrow-left mr-2"></i>
        戻る
      </a>
    </div>

    <!-- メイン投稿カード -->
    <article class="post-detail animate-fade-in">
      <!-- 投稿ヘッダー -->
      <header class="post-detail-header">
        <a href="{{ route('profile.show', $post->user) }}"
          class="flex items-center group">
          <div class="relative">
            <img src="{{ $post->user->avatar_url }}"
              alt="{{ $post->user->name }}"
              class="post-detail-avatar group-hover:scale-105 transition-transform duration-300">
            <!-- オンラインインジケーター -->
            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-success-500 rounded-full border-2 border-white flex items-center justify-center">
              <div class="w-2 h-2 bg-white rounded-full"></div>
            </div>
          </div>
          <div class="post-detail-author-info">
            <h2 class="group-hover:text-mocha-600 transition-colors duration-300">
              {{ $post->user->name }}
            </h2>
            <div class="post-detail-meta">
              <span class="flex items-center gap-1">
                <i class="fas fa-clock text-neutral-400"></i>
                <time datetime="{{ $post->created_at->toISOString() }}">
                  {{ $post->created_at->format('Y年n月j日 H:i') }}
                </time>
              </span>
              <span class="flex items-center gap-1">
                <i class="fas fa-eye text-neutral-400"></i>
                123 views
              </span>
            </div>
          </div>
        </a>

        <!-- 投稿ステータス -->
        <div class="ml-auto">
          <span class="visit-status-badge {{ $post->visit_status ? 'visit-status-visited' : 'visit-status-planned' }}">
            <i class="fas fa-{{ $post->visit_status ? 'check-circle' : 'heart' }}"></i>
            {{ $post->visit_status ? '訪問済み' : '行きたい' }}
          </span>
        </div>
      </header>

      <!-- 店舗情報セクション -->
      <section class="post-info">
        <h3 class="flex items-center gap-2 mb-4">
          <i class="fas fa-store text-mocha-500"></i>
          店舗情報
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div>
              <h4 class="text-2xl font-bold text-neutral-900 mb-2">
                <a href="{{ route('shops.show', $post->shop->id) }}"
                  class="post-shop-link hover:text-mocha-700">
                  {{ $post->shop->name }}
                </a>
              </h4>
              @if($post->shop->address)
              <p class="text-neutral-600 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-neutral-400"></i>
                {{ $post->shop->address }}
              </p>
              @endif
            </div>

            @if($post->visit_time)
            <div class="flex items-center gap-2 text-neutral-600">
              <i class="fas fa-calendar-alt text-mocha-500"></i>
              <span>訪問日時: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y年n月j日 H:i') }}</span>
            </div>
            @endif

            @if($post->budget)
            <div class="flex items-center gap-2">
              <i class="fas fa-yen-sign text-warning-500"></i>
              <span class="font-semibold text-warning-700">
                予算: {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
              </span>
            </div>
            @endif
          </div>

          <!-- 店舗アクション -->
          <div class="flex flex-col gap-3">
            <a href="{{ route('shops.show', $post->shop->id) }}"
              class="btn btn-outline-primary">
              <i class="fas fa-info-circle mr-2"></i>
              店舗詳細を見る
            </a>

            @if($post->reference_url)
            <a href="{{ $post->reference_url }}"
              target="_blank"
              rel="noopener noreferrer"
              class="btn btn-outline-secondary">
              <i class="fas fa-external-link-alt mr-2"></i>
              公式サイト
            </a>
            @endif

            <button onclick="addToWishlist({{ $post->shop->id }})"
              class="btn btn-outline-secondary">
              <i class="fas fa-bookmark mr-2"></i>
              お気に入りに追加
            </button>
          </div>
        </div>
      </section>

      <!-- 投稿本文 -->
      @if($post->body)
      <section class="post-memo">
        <h3 class="flex items-center gap-2 mb-4">
          <i class="fas fa-comment-dots text-electric-500"></i>
          体験・感想
        </h3>
        <div class="prose prose-neutral max-w-none">
          <p class="text-neutral-700 leading-relaxed whitespace-pre-line text-lg">
            {{ $post->body }}
          </p>
        </div>
      </section>
      @endif

      <!-- おすすめメニュー -->
      @if($post->menus)
      <section class="post-menus">
        <h3 class="flex items-center gap-2 mb-4">
          <i class="fas fa-utensils text-sage-500"></i>
          おすすめメニュー
        </h3>
        <div class="bg-gradient-to-r from-sage-50 to-sage-100 rounded-xl p-6 border border-sage-200">
          <p class="text-sage-800 leading-relaxed whitespace-pre-line">
            {{ $post->menus }}
          </p>
        </div>
      </section>
      @endif

      <!-- 参考情報 -->
      @if($post->reference_url)
      <section class="post-reference">
        <h3 class="flex items-center gap-2 mb-4">
          <i class="fas fa-link text-coral-500"></i>
          参考情報
        </h3>
        <a href="{{ $post->reference_url }}"
          target="_blank"
          rel="noopener noreferrer"
          class="glass-subtle rounded-xl p-4 flex items-center gap-4 hover:bg-white/50 transition-colors duration-200">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-coral-400 to-coral-600 flex items-center justify-center text-white flex-shrink-0">
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

      <!-- フォルダ・タグ -->
      @if($post->folders && $post->folders->count() > 0)
      <section class="post-folders">
        <h3 class="flex items-center gap-2 mb-4">
          <i class="fas fa-tags text-neutral-500"></i>
          タグ
        </h3>
        <div class="flex flex-wrap gap-2">
          @foreach($post->folders as $folder)
          <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-neutral-100 to-neutral-200 text-neutral-700 text-sm font-medium rounded-full hover:from-mocha-100 hover:to-mocha-200 hover:text-mocha-700 transition-all duration-200 cursor-pointer">
            <i class="fas fa-tag text-xs"></i>
            {{ $folder->name }}
          </span>
          @endforeach
        </div>
      </section>
      @endif

      <!-- 投稿統計・アクション -->
      <div class="flex items-center justify-between pt-6 border-t border-neutral-200">
        <div class="flex items-center gap-6">
          <button onclick="toggleLike({{ $post->id }})"
            class="flex items-center gap-2 text-neutral-600 hover:text-coral-600 transition-colors duration-200">
            <i class="far fa-heart text-lg"></i>
            <span id="like-count">12</span>
          </button>

          <a href="#comments"
            class="flex items-center gap-2 text-neutral-600 hover:text-electric-600 transition-colors duration-200">
            <i class="far fa-comment text-lg"></i>
            <span>3</span>
          </a>

          <button onclick="sharePost()"
            class="flex items-center gap-2 text-neutral-600 hover:text-sage-600 transition-colors duration-200">
            <i class="fas fa-share text-lg"></i>
            <span>共有</span>
          </button>
        </div>

        <div class="flex items-center gap-2 text-sm text-neutral-500">
          <i class="fas fa-eye"></i>
          <span>123 views</span>
        </div>
      </div>
    </article>

    <!-- 関連投稿 -->
    <section class="mt-12 animate-slide-up">
      <h3 class="text-2xl font-bold text-gradient-mocha mb-6 flex items-center gap-3">
        <i class="fas fa-heart text-coral-500"></i>
        この投稿をした人の他の投稿
      </h3>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($post->user->posts->where('id', '!=', $post->id)->take(4) as $relatedPost)
        <div class="glass-card p-6 hover-lift">
          <div class="flex items-center gap-3 mb-4">
            <img src="{{ $relatedPost->user->avatar_url }}"
              alt="{{ $relatedPost->user->name }}"
              class="w-10 h-10 rounded-full object-cover">
            <div>
              <div class="font-semibold text-neutral-900">{{ $relatedPost->user->name }}</div>
              <div class="text-sm text-neutral-500">{{ $relatedPost->created_at->diffForHumans() }}</div>
            </div>
          </div>

          <h4 class="font-semibold text-neutral-900 mb-2">
            <a href="{{ route('posts.show', $relatedPost->id) }}"
              class="hover:text-mocha-600 transition-colors duration-200">
              {{ $relatedPost->shop->name }}
            </a>
          </h4>

          @if($relatedPost->body)
          <p class="text-neutral-600 text-sm leading-relaxed mb-4">
            {{ Str::limit($relatedPost->body, 100) }}
          </p>
          @endif

          <div class="flex items-center justify-between">
            <span class="visit-status-badge {{ $relatedPost->visit_status ? 'visit-status-visited' : 'visit-status-planned' }}">
              <i class="fas fa-{{ $relatedPost->visit_status ? 'check-circle' : 'heart' }}"></i>
              {{ $relatedPost->visit_status ? '訪問済み' : '行きたい' }}
            </span>

            <a href="{{ route('posts.show', $relatedPost->id) }}"
              class="text-mocha-600 hover:text-mocha-700 text-sm font-medium">
              詳細を見る
            </a>
          </div>
        </div>
        @endforeach
      </div>
    </section>

    <!-- コメントセクション -->
    <section id="comments" class="mt-12 animate-slide-up">
      <h3 class="text-2xl font-bold text-gradient-mocha mb-6 flex items-center gap-3">
        <i class="fas fa-comments text-electric-500"></i>
        コメント (3)
      </h3>

      @auth
      <!-- コメント投稿フォーム -->
      <form class="glass-card p-6 mb-8"
        onsubmit="submitComment(event)">
        @csrf
        <div class="flex items-start gap-4">
          <img src="{{ auth()->user()->avatar_url }}"
            alt="{{ auth()->user()->name }}"
            class="w-12 h-12 rounded-full object-cover border-2 border-mocha-200">
          <div class="flex-1">
            <textarea name="body"
              placeholder="コメントを入力..."
              rows="3"
              class="form-textarea-base mb-3"
              required></textarea>
            <div class="flex items-center justify-between">
              <div class="text-sm text-neutral-500">
                <i class="fas fa-info-circle mr-1"></i>
                建設的なコメントをお願いします
              </div>
              <button type="submit"
                class="btn btn-primary">
                <i class="fas fa-paper-plane mr-2"></i>
                投稿
              </button>
            </div>
          </div>
        </div>
      </form>
      @else
      <!-- ログイン促進 -->
      <div class="glass-card p-6 text-center mb-8">
        <i class="fas fa-comments text-4xl text-neutral-400 mb-4"></i>
        <h4 class="text-lg font-semibold text-neutral-700 mb-2">コメントを投稿</h4>
        <p class="text-neutral-500 mb-4">
          コメントを投稿するにはログインが必要です
        </p>
        <div class="flex items-center justify-center gap-3">
          <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="fas fa-sign-in-alt mr-2"></i>
            ログイン
          </a>
          <a href="{{ route('register') }}" class="btn btn-outline-primary">
            <i class="fas fa-user-plus mr-2"></i>
            新規登録
          </a>
        </div>
      </div>
      @endauth

      <!-- コメント一覧 -->
      <div class="space-y-6">
        <!-- コメント1 -->
        <div class="glass-card p-6">
          <div class="flex items-start gap-4">
            <img src="https://via.placeholder.com/48"
              alt="ユーザー"
              class="w-12 h-12 rounded-full object-cover">
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-2">
                <span class="font-semibold text-neutral-900">田中太郎</span>
                <span class="text-sm text-neutral-500">2時間前</span>
              </div>
              <p class="text-neutral-700 leading-relaxed mb-3">
                この店舗、私も先週行きました！パスタが本当に美味しかったです。
                特におすすめのカルボナーラは絶品でした。
              </p>
              <div class="flex items-center gap-4">
                <button class="text-neutral-500 hover:text-coral-600 text-sm">
                  <i class="far fa-heart mr-1"></i>
                  いいね (2)
                </button>
                <button class="text-neutral-500 hover:text-electric-600 text-sm">
                  <i class="fas fa-reply mr-1"></i>
                  返信
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- コメント2 -->
        <div class="glass-card p-6">
          <div class="flex items-start gap-4">
            <img src="https://via.placeholder.com/48"
              alt="ユーザー"
              class="w-12 h-12 rounded-full object-cover">
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-2">
                <span class="font-semibold text-neutral-900">佐藤花子</span>
                <span class="text-sm text-neutral-500">5時間前</span>
              </div>
              <p class="text-neutral-700 leading-relaxed mb-3">
                雰囲気もとても良さそうですね！今度友達と一緒に行ってみたいと思います。
                予約は必要でしょうか？
              </p>
              <div class="flex items-center gap-4">
                <button class="text-neutral-500 hover:text-coral-600 text-sm">
                  <i class="far fa-heart mr-1"></i>
                  いいね (1)
                </button>
                <button class="text-neutral-500 hover:text-electric-600 text-sm">
                  <i class="fas fa-reply mr-1"></i>
                  返信
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- コメント3（返信付き） -->
        <div class="glass-card p-6">
          <div class="flex items-start gap-4">
            <img src="https://via.placeholder.com/48"
              alt="ユーザー"
              class="w-12 h-12 rounded-full object-cover">
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-2">
                <span class="font-semibold text-neutral-900">山田次郎</span>
                <span class="text-sm text-neutral-500">1日前</span>
              </div>
              <p class="text-neutral-700 leading-relaxed mb-3">
                素敵な投稿ありがとうございます！写真も綺麗で、とても参考になります。
              </p>
              <div class="flex items-center gap-4 mb-4">
                <button class="text-neutral-500 hover:text-coral-600 text-sm">
                  <i class="far fa-heart mr-1"></i>
                  いいね (3)
                </button>
                <button class="text-neutral-500 hover:text-electric-600 text-sm">
                  <i class="fas fa-reply mr-1"></i>
                  返信
                </button>
              </div>

              <!-- 返信コメント -->
              <div class="ml-8 pt-4 border-t border-neutral-200">
                <div class="flex items-start gap-4">
                  <img src="{{ $post->user->avatar_url }}"
                    alt="{{ $post->user->name }}"
                    class="w-10 h-10 rounded-full object-cover">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="font-semibold text-neutral-900">{{ $post->user->name }}</span>
                      <span class="text-xs bg-mocha-100 text-mocha-800 px-2 py-1 rounded-full">投稿者</span>
                      <span class="text-sm text-neutral-500">20時間前</span>
                    </div>
                    <p class="text-neutral-700 leading-relaxed text-sm">
                      ありがとうございます！ぜひ行ってみてください。
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- JavaScript -->
  <script>
    // いいね機能
    function toggleLike(postId) {
      const likeButton = event.currentTarget;
      const icon = likeButton.querySelector('i');
      const countSpan = likeButton.querySelector('span');
      const currentCount = parseInt(countSpan.textContent);

      if (icon.classList.contains('far')) {
        // いいねを付ける
        icon.classList.remove('far');
        icon.classList.add('fas');
        countSpan.textContent = currentCount + 1;
        likeButton.classList.add('text-coral-600');

        // アニメーション効果
        icon.classList.add('animate-bounce-gentle');
        setTimeout(() => {
          icon.classList.remove('animate-bounce-gentle');
        }, 600);
      } else {
        // いいねを外す
        icon.classList.remove('fas');
        icon.classList.add('far');
        countSpan.textContent = currentCount - 1;
        likeButton.classList.remove('text-coral-600');
      }
    }

    // 共有機能
    function sharePost() {
      const url = window.location.href;
      const title = document.title;

      if (navigator.share) {
        navigator.share({
          title: title,
          url: url
        });
      } else {
        // フォールバック: URLをクリップボードにコピー
        navigator.clipboard.writeText(url).then(() => {
          showToast('URLをクリップボードにコピーしました', 'success');
        });
      }
    }

    // お気に入り追加
    function addToWishlist(shopId) {
      // 実際の実装では、APIリクエストを送信
      showToast('お気に入りに追加しました', 'success');
    }

    // コメント投稿
    function submitComment(event) {
      event.preventDefault();

      const form = event.target;
      const formData = new FormData(form);

      // 実際の実装では、APIリクエストを送信
      showToast('コメントを投稿しました', 'success');
      form.reset();
    }

    // トースト通知
    function showToast(message, type = 'info') {
      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'info'} mr-2"></i>
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

    // スムーススクロール
    document.addEventListener('DOMContentLoaded', function() {
      const commentLink = document.querySelector('a[href="#comments"]');
      if (commentLink) {
        commentLink.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('comments').scrollIntoView({
            behavior: 'smooth'
          });
        });
      }
    });
  </script>
</x-app-layout>