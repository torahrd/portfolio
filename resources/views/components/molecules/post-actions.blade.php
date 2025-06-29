@props([
'postId',
'likesCount' => 0,
'commentsCount' => 0,
'isFavorited' => false,
])

<div class="flex items-center justify-between pt-3 border-t border-neutral-100">
  <div class="flex items-center space-x-4">
    <!-- いいねボタン -->
    <button
      type="button"
      data-post-id="{{ $postId }}"
      data-is-favorited="{{ $isFavorited ? 'true' : 'false' }}"
      class="like-button flex items-center space-x-1 transition-colors duration-200 {{ $isFavorited ? 'text-red-500' : 'text-neutral-500 hover:text-red-500' }}"
      {{ !auth()->check() ? 'disabled' : '' }}>
      <svg class="w-4 h-4 like-icon" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
      </svg>
      <span class="text-sm like-count">{{ $likesCount }}</span>
    </button>

    <!-- コメントボタン -->
    <a href="{{ route('posts.show', $postId) }}#comments" class="flex items-center space-x-1 text-neutral-500 hover:text-primary-500 transition-colors duration-200">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
      </svg>
      <span class="text-sm">{{ $commentsCount }}</span>
    </a>
  </div>

  <!-- 投稿詳細リンク -->
  <a href="{{ route('posts.show', $postId) }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium">
    詳細
  </a>
</div>