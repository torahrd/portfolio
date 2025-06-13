<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-2xl text-neutral-800 leading-tight flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-mocha-500 to-sage-500 flex items-center justify-center text-white">
          <i class="fas fa-user text-lg"></i>
        </div>
        {{ $user->name }}のプロフィール
      </h2>
    </div>
  </x-slot>

  <div class="max-w-6xl mx-auto">
    <!-- メッセージ表示エリア -->
    <div id="message-area" class="mb-6"></div>

    <!-- 戻るボタン -->
    <div class="mb-6 animate-slide-down">
      <a href="{{ url()->previous() }}"
        class="btn btn-outline-secondary hover-lift">
        <i class="fas fa-arrow-left mr-2"></i>
        戻る
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- プロフィールサイドバー -->
      <div class="lg:col-span-1 space-y-6">
        <!-- プロフィールカード -->
        <div class="glass-card p-6 text-center animate-slide-left">
          <!-- プロフィール画像 -->
          <div class="relative mb-6">
            <div class="w-32 h-32 mx-auto rounded-full overflow-hidden shadow-2xl border-4 border-white">
              <img src="{{ $user->avatar_url }}"
                alt="{{ $user->name }}"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
            </div>

            <!-- オンライン状態インジケーター -->
            <div class="absolute bottom-2 right-1/2 transform translate-x-12">
              <div class="w-6 h-6 bg-success-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
              </div>
            </div>
          </div>

          <!-- ユーザー名 -->
          <h1 class="text-2xl font-bold text-neutral-900 mb-2">
            {{ $user->name }}
          </h1>

          <!-- プロフィール説明 -->
          @if($user->bio)
          <p class="text-neutral-600 text-sm leading-relaxed mb-6 px-2">
            {{ $user->bio }}
          </p>
          @endif

          <!-- プロフィール統計 -->
          <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="glass-subtle rounded-xl p-3">
              <div class="text-2xl font-bold text-mocha-600">{{ $user->posts_count ?? 0 }}</div>
              <div class="text-xs text-neutral-500 uppercase tracking-wide">投稿</div>
            </div>
            <div class="glass-subtle rounded-xl p-3">
              <a href="{{ route('profile.followers', $user) }}"
                class="block hover:bg-white/20 transition-colors duration-200 rounded-lg">
                <div class="text-2xl font-bold text-sage-600">{{ $user->followers_count ?? 0 }}</div>
                <div class="text-xs text-neutral-500 uppercase tracking-wide">フォロワー</div>
              </a>
            </div>
            <div class="glass-subtle rounded-xl p-3">
              <a href="{{ route('profile.following', $user) }}"
                class="block hover:bg-white/20 transition-colors duration-200 rounded-lg">
                <div class="text-2xl font-bold text-electric-600">{{ $user->following_count ?? 0 }}</div>
                <div class="text-xs text-neutral-500 uppercase tracking-wide">フォロー中</div>
              </a>
            </div>
          </div>

          <!-- フォローボタン -->
          @auth
          @if(auth()->id() !== $user->id)
          <div class="space-y-3" x-data="{ 
                        following: {{ $isFollowing ? 'true' : 'false' }}, 
                        pending: {{ $hasPendingRequest ? 'true' : 'false' }},
                        loading: false 
                    }">
            <button @click="toggleFollow()"
              :disabled="loading"
              class="w-full btn transition-all duration-300"
              :class="{
                                    'btn-primary': !following && !pending,
                                    'btn-outline-secondary': following,
                                    'btn-outline-warning': pending,
                                    'opacity-50 cursor-not-allowed': loading
                                }">
              <template x-if="loading">
                <i class="fas fa-spinner animate-spin mr-2"></i>
              </template>

              <template x-if="!loading && !following && !pending">
                <span><i class="fas fa-user-plus mr-2"></i>フォローする</span>
              </template>

              <template x-if="!loading && following">
                <span><i class="fas fa-user-check mr-2"></i>フォロー中</span>
              </template>

              <template x-if="!loading && pending">
                <span><i class="fas fa-clock mr-2"></i>承認待ち</span>
              </template>
            </button>

            <!-- プライベートメッセージボタン（将来の機能） -->
            <button class="w-full btn btn-outline-secondary" disabled>
              <i class="fas fa-envelope mr-2"></i>
              メッセージ
              <span class="text-xs opacity-50 ml-2">(準備中)</span>
            </button>
          </div>
          @endif
          @endauth

          <!-- プロフィール編集ボタン（自分のプロフィールの場合） -->
          @auth
          @if(auth()->id() === $user->id)
          <div class="space-y-3">
            <a href="{{ route('profile.edit') }}"
              class="w-full btn btn-primary">
              <i class="fas fa-edit mr-2"></i>
              プロフィールを編集
            </a>

            @if($user->is_private)
            <button onclick="generateProfileLink()"
              class="w-full btn btn-outline-secondary">
              <i class="fas fa-link mr-2"></i>
              プロフィールリンク生成
            </button>
            @endif
          </div>
          @endif
          @endauth
        </div>

        <!-- 活動統計カード -->
        <div class="glass-card p-6 animate-slide-left" style="animation-delay: 0.2s;">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-mocha-500"></i>
            活動統計
          </h3>

          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-sm text-neutral-600">今月の投稿</span>
              <span class="font-semibold text-mocha-600">12</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-neutral-600">訪問済み店舗</span>
              <span class="font-semibold text-sage-600">48</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-neutral-600">行きたい店舗</span>
              <span class="font-semibold text-electric-600">23</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-neutral-600">参加日</span>
              <span class="font-semibold text-neutral-700">
                {{ $user->created_at->format('Y年n月') }}
              </span>
            </div>
          </div>

          <!-- プログレスバー -->
          <div class="mt-4 pt-4 border-t border-neutral-200">
            <div class="text-sm text-neutral-600 mb-2">美食レベル</div>
            <div class="progress-bar">
              <div class="progress-bar-fill w-3/4"></div>
            </div>
            <div class="text-xs text-neutral-500 mt-1">レベル 7 - 美食探検家</div>
          </div>
        </div>

        <!-- バッジ・実績カード -->
        <div class="glass-card p-6 animate-slide-left" style="animation-delay: 0.4s;">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center gap-2">
            <i class="fas fa-medal text-warning-500"></i>
            バッジ・実績
          </h3>

          <div class="grid grid-cols-3 gap-3">
            <div class="text-center p-3 glass-subtle rounded-xl hover:bg-white/20 transition-colors duration-200">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-warning-400 to-warning-600 flex items-center justify-center text-white mx-auto mb-2">
                <i class="fas fa-star text-sm"></i>
              </div>
              <div class="text-xs text-neutral-600">初投稿</div>
            </div>

            <div class="text-center p-3 glass-subtle rounded-xl hover:bg-white/20 transition-colors duration-200">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-success-400 to-success-600 flex items-center justify-center text-white mx-auto mb-2">
                <i class="fas fa-fire text-sm"></i>
              </div>
              <div class="text-xs text-neutral-600">連続投稿</div>
            </div>

            <div class="text-center p-3 glass-subtle rounded-xl hover:bg-white/20 transition-colors duration-200">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-electric-400 to-electric-600 flex items-center justify-center text-white mx-auto mb-2">
                <i class="fas fa-users text-sm"></i>
              </div>
              <div class="text-xs text-neutral-600">人気投稿</div>
            </div>

            <div class="text-center p-3 glass-subtle rounded-xl hover:bg-white/20 transition-colors duration-200">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-coral-400 to-coral-600 flex items-center justify-center text-white mx-auto mb-2">
                <i class="fas fa-heart text-sm"></i>
              </div>
              <div class="text-xs text-neutral-600">いいね達人</div>
            </div>

            <div class="text-center p-3 glass-subtle rounded-xl hover:bg-white/20 transition-colors duration-200">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-mocha-400 to-mocha-600 flex items-center justify-center text-white mx-auto mb-2">
                <i class="fas fa-compass text-sm"></i>
              </div>
              <div class="text-xs text-neutral-600">探検家</div>
            </div>

            <div class="text-center p-3 glass-subtle rounded-xl opacity-50">
              <div class="w-8 h-8 rounded-full bg-neutral-300 flex items-center justify-center text-neutral-500 mx-auto mb-2">
                <i class="fas fa-question text-sm"></i>
              </div>
              <div class="text-xs text-neutral-400">???</div>
            </div>
          </div>
        </div>
      </div>

      <!-- メインコンテンツエリア -->
      <div class="lg:col-span-3 space-y-6">
        <!-- タブナビゲーション -->
        <div class="glass-card p-2 animate-slide-right">
          <div class="neu-tabs">
            <button class="neu-tab active" data-tab="posts">
              <i class="fas fa-utensils mr-2"></i>
              投稿 ({{ $user->posts->count() }})
            </button>
            <button class="neu-tab" data-tab="favorites">
              <i class="fas fa-heart mr-2"></i>
              お気に入り
            </button>
            <button class="neu-tab" data-tab="visited">
              <i class="fas fa-check-circle mr-2"></i>
              訪問済み
            </button>
            <button class="neu-tab" data-tab="wishlist">
              <i class="fas fa-bookmark mr-2"></i>
              行きたい
            </button>
          </div>
        </div>

        <!-- 投稿一覧 -->
        <div id="posts-tab" class="tab-content">
          @if($user->posts->count() > 0)
          <div class="space-y-6">
            @foreach($user->posts->take(10) as $index => $post)
            <article class="post-item animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s">
              <!-- 投稿ヘッダー -->
              <header class="post-header">
                <div class="flex items-center">
                  <img src="{{ $post->user->avatar_url }}"
                    alt="{{ $post->user->name }}"
                    class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-neutral-200">
                  <div class="flex-1">
                    <div class="font-semibold text-neutral-900">{{ $post->user->name }}</div>
                    <div class="text-sm text-neutral-500 flex items-center gap-3">
                      <span>
                        <i class="fas fa-clock mr-1"></i>
                        {{ $post->created_at->diffForHumans() }}
                      </span>
                      @if($post->visit_time)
                      <span>
                        <i class="fas fa-calendar-alt mr-1"></i>
                        訪問: {{ \Carbon\Carbon::parse($post->visit_time)->format('Y/m/d') }}
                      </span>
                      @endif
                      <span class="visit-status-badge {{ $post->visit_status ? 'visit-status-visited' : 'visit-status-planned' }}">
                        <i class="fas fa-{{ $post->visit_status ? 'check-circle' : 'heart' }}"></i>
                        {{ $post->visit_status ? '訪問済み' : '行きたい' }}
                      </span>
                    </div>
                  </div>
                </div>
              </header>

              <!-- 投稿内容 -->
              <div class="post-content">
                <h3 class="text-xl font-semibold mb-3">
                  <a href="{{ route('shops.show', $post->shop->id) }}"
                    class="post-shop-link">
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
                <a href="{{ route('posts.show', $post->id) }}"
                  class="btn btn-primary">
                  <i class="fas fa-eye mr-1"></i>詳細を見る
                </a>
                @auth
                @if(auth()->id() === $post->user_id)
                <a href="{{ route('posts.edit', $post->id) }}"
                  class="btn btn-outline-secondary">
                  <i class="fas fa-edit mr-1"></i>編集
                </a>
                @endif
                @endauth
              </div>
            </article>
            @endforeach
          </div>
          @else
          <!-- 投稿なしの状態 -->
          <div class="text-center py-16">
            <div class="glass-card p-12 max-w-md mx-auto">
              <div class="w-20 h-20 rounded-full bg-gradient-to-br from-neutral-200 to-neutral-300 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-inbox text-3xl text-neutral-400"></i>
              </div>
              <h3 class="text-xl font-semibold text-neutral-700 mb-4">
                まだ投稿がありません
              </h3>
              <p class="text-neutral-500 mb-6 leading-relaxed">
                @if(auth()->check() && auth()->id() === $user->id)
                最初の投稿を作成して、<br>あなたのお気に入りの店舗を共有しましょう。
                @else
                {{ $user->name }}さんの投稿をお待ちしています。
                @endif
              </p>
              @if(auth()->check() && auth()->id() === $user->id)
              <a href="{{ route('posts.create') }}"
                class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                最初の投稿を作成
              </a>
              @endif
            </div>
          </div>
          @endif
        </div>

        <!-- その他のタブコンテンツ（準備中） -->
        <div id="favorites-tab" class="tab-content hidden">
          <div class="glass-card p-12 text-center">
            <i class="fas fa-heart text-4xl text-coral-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-neutral-700 mb-2">お気に入り機能</h3>
            <p class="text-neutral-500">この機能は準備中です</p>
          </div>
        </div>

        <div id="visited-tab" class="tab-content hidden">
          <div class="glass-card p-12 text-center">
            <i class="fas fa-check-circle text-4xl text-success-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-neutral-700 mb-2">訪問済み店舗</h3>
            <p class="text-neutral-500">この機能は準備中です</p>
          </div>
        </div>

        <div id="wishlist-tab" class="tab-content hidden">
          <div class="glass-card p-12 text-center">
            <i class="fas fa-bookmark text-4xl text-electric-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-neutral-700 mb-2">行きたい店舗</h3>
            <p class="text-neutral-500">この機能は準備中です</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // タブ切り替え機能
    document.addEventListener('DOMContentLoaded', function() {
      const tabs = document.querySelectorAll('.neu-tab');
      const tabContents = document.querySelectorAll('.tab-content');

      tabs.forEach(tab => {
        tab.addEventListener('click', function() {
          const targetTab = this.dataset.tab;

          // アクティブ状態の更新
          tabs.forEach(t => t.classList.remove('active'));
          this.classList.add('active');

          // コンテンツの表示/非表示
          tabContents.forEach(content => {
            if (content.id === targetTab + '-tab') {
              content.classList.remove('hidden');
              content.classList.add('animate-fade-in');
            } else {
              content.classList.add('hidden');
              content.classList.remove('animate-fade-in');
            }
          });
        });
      });
    });

    // フォロー機能
    function toggleFollow() {
      const data = this.$data;
      data.loading = true;

      // 実際の実装では、ここでサーバーにリクエストを送信
      setTimeout(() => {
        if (data.following) {
          data.following = false;
        } else if (data.pending) {
          data.pending = false;
        } else {
          // プライベートアカウントの場合は pending、そうでなければ following
          const isPrivate = {
            {
              $user - > is_private ? 'true' : 'false'
            }
          };
          if (isPrivate) {
            data.pending = true;
          } else {
            data.following = true;
          }
        }
        data.loading = false;

        // 成功メッセージを表示
        const message = data.following ? 'フォローしました' :
          data.pending ? 'フォロー申請を送信しました' :
          'フォローを解除しました';
        showMessage(message, 'success');
      }, 1000);
    }

    // プロフィールリンク生成
    function generateProfileLink() {
      fetch('{{ route("profile.generate-link") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            navigator.clipboard.writeText(data.link).then(() => {
              showMessage(`プロフィールリンクをクリップボードにコピーしました\n（有効期限: ${data.expires_at}）`, 'success');
            });
          } else {
            showMessage(data.error, 'error');
          }
        })
        .catch(error => {
          showMessage('エラーが発生しました', 'error');
        });
    }

    // メッセージ表示
    function showMessage(message, type) {
      const messageArea = document.getElementById('message-area');
      const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
      const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

      messageArea.innerHTML = `
                <div class="${alertClass} alert animate-slide-down" 
                     x-data="{ show: true }" 
                     x-show="show"
                     x-init="setTimeout(() => show = false, 5000)">
                    <i class="alert-icon fas ${icon}"></i>
                    <div class="alert-content">${message}</div>
                    <button onclick="this.parentElement.remove()" class="alert-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
    }
  </script>
</x-app-layout>