@props([
'post',
'showActions' => true,
'compact' => false
])

<article class="bg-white rounded-xl shadow-card hover:shadow-card-hover transition-all duration-300 overflow-hidden group {{ $compact ? 'p-3' : 'p-4' }}">
  <!-- 投稿画像 -->
  <div class="relative mb-3">
    <x-atoms.image
      :src="$post->image_url ?? '/images/placeholder-food.jpg'"
      :alt="$post->shop->name ?? '店舗画像'"
      aspect-ratio="16/9"
      rounded="lg"
      class="group-hover:scale-105 transition-transform duration-300" />

    <!-- 訪問ステータスバッジ -->
    @if(isset($post->visit_status))
    <div class="absolute top-2 right-2">
      <span class="px-2 py-1 text-xs font-medium rounded-full {{ $post->visit_status ? 'bg-success-100 text-success-800' : 'bg-warning-100 text-warning-800' }}">
        {{ $post->visit_status ? '訪問済み' : '訪問予定' }}
      </span>
    </div>
    @endif
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
    </div>

    <!-- 投稿本文（もしある場合） -->
    @if($post->content ?? false)
    <p class="text-sm text-neutral-600 line-clamp-2">
      {{ $post->content }}
    </p>
    @endif

    <!-- アクション（いいね、コメント） -->
    @if($showActions)
    <div class="flex items-center justify-between pt-3 border-t border-neutral-100">
      <div class="flex items-center space-x-4">
        <!-- いいねボタン -->
        <button class="flex items-center space-x-1 text-neutral-500 hover:text-primary-500 transition-colors duration-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
          </svg>
          <span class="text-sm">{{ $post->likes_count ?? 0 }}</span>
        </button>

        <!-- コメントボタン -->
        <button class="flex items-center space-x-1 text-neutral-500 hover:text-primary-500 transition-colors duration-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
          </svg>
          <span class="text-sm">{{ $post->comments_count ?? 0 }}</span>
        </button>
      </div>

      <!-- 投稿詳細リンク -->
      <a href="{{ route('posts.show', $post) }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium">
        詳細
      </a>
    </div>
    @endif
  </div>
</article>