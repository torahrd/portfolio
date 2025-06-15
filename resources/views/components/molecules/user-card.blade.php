@props([
'user',
'showActions' => true,
'compact' => false
])

<div class="bg-white rounded-xl shadow-card p-6 hover:shadow-card-hover transition-shadow duration-300">
  <div class="flex items-center space-x-4">
    <!-- アバター -->
    <x-atoms.avatar
      :src="$user->avatar_url"
      :alt="$user->name"
      :size="$compact ? 'md' : 'lg'"
      :online="$user->is_online ?? false" />

    <!-- ユーザー情報 -->
    <div class="flex-1 min-w-0">
      <div class="flex items-center space-x-2">
        <h4 class="text-lg font-semibold text-neutral-900 truncate">
          <a href="{{ route('profile.show', $user) }}" class="hover:text-primary-500 transition-colors duration-200">
            {{ $user->name }}
          </a>
        </h4>

        @if($user->is_verified ?? false)
        <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        @endif
      </div>

      @if($user->bio)
      <p class="text-sm text-neutral-600 mt-1 {{ $compact ? 'line-clamp-1' : 'line-clamp-2' }}">
        {{ $user->bio }}
      </p>
      @endif

      <!-- 統計情報 -->
      <div class="flex items-center space-x-4 mt-3 text-sm text-neutral-500">
        @if(isset($user->posts_count))
        <span>投稿 {{ number_format($user->posts_count) }}</span>
        @endif
        @if(isset($user->followers_count))
        <span>フォロワー {{ number_format($user->followers_count) }}</span>
        @endif
        @if(isset($user->following_count))
        <span>フォロー中 {{ number_format($user->following_count) }}</span>
        @endif
      </div>
    </div>

    <!-- アクションボタン -->
    @if($showActions && Auth::id() !== $user->id)
    <div class="flex-shrink-0">
      @if($user->is_following ?? false)
      <x-atoms.button variant="secondary" size="sm">
        フォロー中
      </x-atoms.button>
      @else
      <x-atoms.button variant="primary" size="sm">
        フォロー
      </x-atoms.button>
      @endif
    </div>
    @endif
  </div>
</div>