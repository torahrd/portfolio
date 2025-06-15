@props([
'user',
'showFollowButton' => true,
'showStats' => true,
'compact' => false
])

<div class="bg-white rounded-xl shadow-card p-4 space-y-4">
  <!-- ユーザー情報ヘッダー -->
  <div class="flex items-center space-x-3">
    <!-- アバター -->
    <div class="flex-shrink-0">
      <img
        src="{{ $user->avatar_url ?? '/images/default-avatar.png' }}"
        alt="{{ $user->name }}"
        class="w-12 h-12 rounded-full object-cover ring-2 ring-neutral-200">
    </div>

    <!-- ユーザー情報 -->
    <div class="flex-1 min-w-0">
      <h3 class="text-base font-semibold text-neutral-900 truncate">
        <a href="{{ route('profile.show', $user) }}" class="hover:text-primary-500 transition-colors duration-200">
          {{ $user->name }}
          @if($user->is_private ?? false)
          <svg class="inline w-3 h-3 ml-1 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
          </svg>
          @endif
        </a>
      </h3>

      @if($user->bio)
      <p class="text-sm text-neutral-600 line-clamp-2">{{ $user->bio }}</p>
      @endif
    </div>

    <!-- フォローボタン -->
    @if($showFollowButton && auth()->check() && auth()->id() !== $user->id)
    <x-atoms.button
      variant="primary"
      size="sm"
      data-user-id="{{ $user->id }}"
      class="follow-btn flex-shrink-0">
      フォロー
    </x-atoms.button>
    @endif
  </div>

  <!-- 統計情報 -->
  @if($showStats && !$compact)
  <div class="grid grid-cols-3 gap-4 pt-3 border-t border-neutral-100">
    <x-molecules.stat-item
      :value="$user->posts_count ?? 0"
      label="投稿"
      href="{{ route('profile.show', $user) }}" />
    <x-molecules.stat-item
      :value="$user->followers_count ?? 0"
      label="フォロワー"
      href="{{ route('profile.followers', $user) }}" />
    <x-molecules.stat-item
      :value="$user->following_count ?? 0"
      label="フォロー中"
      href="{{ route('profile.following', $user) }}" />
  </div>
  @endif
</div>