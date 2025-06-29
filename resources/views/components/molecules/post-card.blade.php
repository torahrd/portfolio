@props([
'post',
'showActions' => true,
'compact' => false
])

<article class="bg-white rounded-xl shadow-card hover:shadow-card-hover transition-all duration-300 overflow-hidden group {{ $compact ? 'p-3' : 'p-4' }}">
  <!-- 投稿画像 -->
  <div class="relative mb-3">
    <a href="{{ route('posts.show', $post) }}" class="block">
      <x-atoms.image
        :src="$post->image_url ?? '/images/placeholder-food.jpg'"
        :alt="$post->shop->name ?? '店舗画像'"
        aspect-ratio="16/9"
        rounded="lg"
        class="group-hover:scale-105 transition-transform duration-300" />
    </a>

    <!-- 訪問ステータスバッジ -->
    <x-molecules.visit-status-badge
      :status="$post->visit_status"
      class="absolute top-2 right-2" />
  </div>

  <!-- 投稿内容 -->
  <div class="space-y-3">
    <!-- 店舗名 -->
    @if(isset($post->shop))
    <h3 class="font-semibold text-lg text-neutral-900 hover:text-primary-500 transition-colors duration-200">
      <a href="{{ route('shops.show', $post->shop) }}" class="line-clamp-2">
        {{ $post->shop->name }}
      </a>
    </h3>
    @endif

    <!-- 投稿者情報 -->
    <div class="flex items-center space-x-3">
      @if($post->user)
      <x-atoms.avatar :user="$post->user" size="small" />
      <div class="flex-1 min-w-0">
        <div class="flex items-center space-x-1">
          <p class="text-sm font-medium text-neutral-900 truncate">
            <a href="{{ route('profile.show', $post->user) }}" class="hover:text-primary-500 transition-colors duration-200">
              {{ $post->user->name }}
            </a>
          </p>
          @if($post->user->is_private)
          <span class="text-neutral-400">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
          </span>
          @endif
        </div>
        <p class="text-xs text-neutral-500">
          {{ $post->created_at->diffForHumans() }}
        </p>
      </div>
      @else
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-neutral-900 truncate">不明なユーザー</p>
        <p class="text-xs text-neutral-500">
          {{ $post->created_at->diffForHumans() }}
        </p>
      </div>
      @endif
    </div>

    <!-- 投稿本文（もしある場合） -->
    @if($post->content ?? false)
    <p class="text-sm text-neutral-600 line-clamp-2">
      {{ $post->content }}
    </p>
    @endif

    <!-- アクション（いいね、コメント） -->
    @if($showActions)
    <x-molecules.post-actions
      :post-id="$post->id"
      :likes-count="$post->favorite_users_count"
      :comments-count="$post->comments_count"
      :is-favorited="auth()->check() ? $post->isFavoritedBy(auth()->id()) : false" />
    @endif
  </div>
</article>