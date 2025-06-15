@props([
'activeTab' => 'home'
])

<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-neutral-200 z-50">
  <div class="grid grid-cols-4 h-16">
    <!-- ホーム -->
    <a
      href="{{ route('posts.index') }}"
      class="flex flex-col items-center justify-center space-y-1 transition-colors duration-200 {{ $activeTab === 'home' ? 'text-primary-500' : 'text-neutral-500 hover:text-neutral-700' }}">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
      </svg>
      <span class="text-xs font-medium">ホーム</span>
    </a>

    <!-- 検索 -->
    <button
      class="flex flex-col items-center justify-center space-y-1 transition-colors duration-200 {{ $activeTab === 'search' ? 'text-primary-500' : 'text-neutral-500 hover:text-neutral-700' }}"
      onclick="toggleMobileSearch()">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
      <span class="text-xs font-medium">検索</span>
    </button>

    <!-- 投稿作成 -->
    @auth
    <a
      href="{{ route('posts.create') }}"
      class="flex flex-col items-center justify-center space-y-1 transition-colors duration-200 {{ $activeTab === 'create' ? 'text-primary-500' : 'text-neutral-500 hover:text-neutral-700' }}">
      <div class="w-8 h-8 {{ $activeTab === 'create' ? 'bg-primary-500' : 'bg-primary-500' }} rounded-full flex items-center justify-center">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
      </div>
      <span class="text-xs font-medium">投稿</span>
    </a>
    @else
    <a
      href="{{ route('login') }}"
      class="flex flex-col items-center justify-center space-y-1 text-neutral-500 hover:text-neutral-700 transition-colors duration-200">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
      </svg>
      <span class="text-xs font-medium">投稿</span>
    </a>
    @endauth

    <!-- マイページ -->
    @auth
    <a
      href="{{ route('profile.show', auth()->user()) }}"
      class="flex flex-col items-center justify-center space-y-1 transition-colors duration-200 {{ $activeTab === 'profile' ? 'text-primary-500' : 'text-neutral-500 hover:text-neutral-700' }}">
      <div class="w-6 h-6 rounded-full overflow-hidden ring-2 {{ $activeTab === 'profile' ? 'ring-primary-500' : 'ring-neutral-200' }}">
        <img
          src="{{ auth()->user()->avatar_url ?? '/images/default-avatar.png' }}"
          alt="{{ auth()->user()->name }}"
          class="w-full h-full object-cover">
      </div>
      <span class="text-xs font-medium">マイページ</span>
    </a>
    @else
    <a
      href="{{ route('login') }}"
      class="flex flex-col items-center justify-center space-y-1 text-neutral-500 hover:text-neutral-700 transition-colors duration-200">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
      </svg>
      <span class="text-xs font-medium">プロフィール</span>
    </a>
    @endauth
  </div>
</nav>

<script>
  function toggleMobileSearch() {
    // モバイル検索機能の実装
    const searchModal = document.getElementById('mobile-search-modal');
    if (searchModal) {
      searchModal.classList.toggle('hidden');
    } else {
      // 検索ページへリダイレクト（実装予定）
      console.log('Mobile search clicked');
    }
  }
</script>