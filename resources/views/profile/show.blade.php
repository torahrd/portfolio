<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ $user->name }}のプロフィール
    </h2>
  </x-slot>

  <div class="container-responsive py-6">
    <!-- メッセージ表示エリア -->
    <div id="message-area"></div>

    <!-- 戻るボタン -->
    <div class="mb-6">
      <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-2"></i>戻る
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- プロフィール情報セクション -->
      <div class="lg:col-span-1">
        <div class="profile-card">
          <div class="profile-header">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar">
            <h1 class="profile-name">{{ $user->name }}</h1>

            @if($user->bio)
            <p class="profile-bio">{{ $user->bio }}</p>
            @endif

            <!-- プロフィール統計 -->
            <div class="profile-stats">
              <div class="stat-item">
                <span class="stat-number">{{ $user->posts_count }}</span>
                <span class="stat-label">投稿</span>
              </div>
              <div class="stat-item">
                <a href="{{ route('profile.followers', $user) }}" class="stat-link">
                  <span class="stat-number">{{ $user->followers_count }}</span>
                  <span class="stat-label">フォロワー</span>
                </a>
              </div>
              <div class="stat-item">
                <a href="{{ route('profile.following', $user) }}" class="stat-link">
                  <span class="stat-number">{{ $user->following_count }}</span>
                  <span class="stat-label">フォロー中</span>
                </a>
              </div>
            </div>

            <!-- フォローボタン -->
            @auth
            @if(auth()->id() !== $user->id)
            <div class="profile-actions">
              <button
                class="follow-btn {{ $isFollowing ? 'following' : '' }} {{ $hasPendingRequest ? 'pending' : '' }}"
                data-user-id="{{ $user->id }}"
                id="followButton{{ $user->id }}"
                x-data="{ 
                                    isFollowing: @json($isFollowing),
                                    isPending: @json($hasPendingRequest),
                                    isLoading: false 
                                }"
                @click="handleFollow"
                :disabled="isLoading">
                <span x-show="!isLoading">
                  <span x-show="isFollowing">フォロー中</span>
                  <span x-show="!isFollowing && isPending">申請中</span>
                  <span x-show="!isFollowing && !isPending">フォロー</span>
                </span>
                <span x-show="isLoading" class="flex items-center">
                  <i class="fas fa-spinner fa-spin mr-2"></i>処理中
                </span>
              </button>
            </div>
            @else
            <div class="profile-actions">
              <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                <i class="fas fa-edit mr-2"></i>プロフィールを編集
              </a>
              @if($user->is_private)
              <button
                class="btn btn-outline-primary mt-2"
                @click="$dispatch('open-profile-link-modal')">
                <i class="fas fa-link mr-2"></i>プロフィールリンクを生成
              </button>
              @endif
            </div>
            @endif
            @endauth

            <!-- 追加情報 -->
            <div class="profile-details">
              @if($user->location)
              <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $user->location }}</span>
              </div>
              @endif

              @if($user->website)
              <div class="detail-item">
                <i class="fas fa-link"></i>
                <a href="{{ $user->website }}" target="_blank" rel="noopener">
                  {{ $user->website }}
                </a>
              </div>
              @endif

              <div class="detail-item">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ $user->created_at->format('Y年n月') }}に参加</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 投稿一覧セクション -->
      <div class="lg:col-span-2">
        <div class="space-y-6">
          <h2 class="section-title">
            <i class="fas fa-utensils mr-2"></i>
            投稿 ({{ $user->posts_count }}件)
          </h2>

          @if($user->posts->count() > 0)
          <div class="space-y-4">
            @foreach($user->posts->take(10) as $post)
            <div class="post-item">
              <!-- 投稿ヘッダー -->
              <div class="post-header">
                <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="post-avatar">
                <div class="post-author-info">
                  <div class="post-author">{{ $post->user->name }}</div>
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
              <div class="space-y-3">
                <h3 class="text-lg font-semibold">
                  <a href="{{ route('shops.show', $post->shop->id) }}" class="shop-link">
                    <i class="fas fa-store shop-icon"></i>
                    {{ $post->shop->name }}
                  </a>
                </h3>

                @if($post->body)
                <p class="text-gray-700 leading-relaxed">{{ Str::limit($post->body, 150) }}</p>
                @endif

                @if($post->budget)
                <p class="budget">
                  予算: {{ App\Helpers\BudgetHelper::formatBudget($post->budget) }}
                </p>
                @endif
              </div>

              <!-- アクション -->
              <div class="actions">
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
            </div>
            @endforeach
          </div>
          @else
          <div class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-xl text-gray-600 mb-2">まだ投稿がありません</h3>
            @if(auth()->check() && auth()->id() === $user->id)
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
              <i class="fas fa-plus mr-2"></i>最初の投稿を作成
            </a>
            @endif
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- プロフィールリンク生成モーダル (Alpine.js版) -->
  @auth
  @if(auth()->id() === $user->id && $user->is_private)
  <div x-data="{ showModal: false }"
    @open-profile-link-modal.window="showModal = true">
    <div x-show="showModal"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-50 overflow-y-auto"
      x-cloak>
      <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showModal = false"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">プロフィールリンクを生成</h3>
            <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <div class="space-y-4">
            <p class="text-gray-600">24時間有効なプロフィールリンクを生成します。</p>

            <div id="profileLinkResult" style="display: none;" class="space-y-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">生成されたリンク:</label>
                <div class="flex">
                  <input type="text" class="form-input rounded-r-none" id="profileLinkUrl" readonly>
                  <button class="btn btn-outline-secondary rounded-l-none" id="copyLinkBtn">
                    コピー
                  </button>
                </div>
              </div>
              <p class="text-sm text-gray-500">
                有効期限: <span id="profileLinkExpiry"></span>
              </p>
            </div>
          </div>

          <div class="flex justify-end space-x-3 mt-6">
            <button @click="showModal = false" class="btn btn-secondary">閉じる</button>
            <button id="generateLinkBtn" class="btn btn-primary">リンクを生成</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  @endauth

  @push('scripts')
  <script>
    // フォロー機能の実装
    document.addEventListener('alpine:init', () => {
      Alpine.data('profileActions', () => ({
        async handleFollow() {
          if (this.isLoading) return;

          this.isLoading = true;

          try {
            const response = await fetch(`/users/{{ $user->id }}/follow`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
            });

            if (response.ok) {
              const data = await response.json();
              this.isFollowing = data.isFollowing;
              this.isPending = data.isPending;

              // メッセージ表示
              if (data.message) {
                MessageDisplay.show(data.message, 'success');
              }
            }
          } catch (error) {
            console.error('Follow error:', error);
            MessageDisplay.show('エラーが発生しました', 'error');
          } finally {
            this.isLoading = false;
          }
        }
      }));
    });

    // プロフィールリンク生成
    document.getElementById('generateLinkBtn')?.addEventListener('click', async function() {
      try {
        const response = await fetch('/profile/generate-link', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });

        if (response.ok) {
          const data = await response.json();
          document.getElementById('profileLinkUrl').value = data.url;
          document.getElementById('profileLinkExpiry').textContent = new Date(data.expires_at).toLocaleString('ja-JP');
          document.getElementById('profileLinkResult').style.display = 'block';
        }
      } catch (error) {
        console.error('Link generation error:', error);
      }
    });

    // リンクコピー機能
    document.getElementById('copyLinkBtn')?.addEventListener('click', function() {
      const urlInput = document.getElementById('profileLinkUrl');
      urlInput.select();
      document.execCommand('copy');
      MessageDisplay.show('リンクをコピーしました', 'success');
    });
  </script>
  @endpush
</x-app-layout>