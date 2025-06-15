@props([
'title' => '',
'showSearch' => true,
'showBack' => false,
'backUrl' => null
])

<header class="bg-white shadow-sm border-b border-neutral-200 sticky top-0 z-40">
  <div class="container mx-auto px-4 py-4">
    <div class="flex items-center justify-between">
      <!-- 左側: ロゴ・タイトル・戻るボタン -->
      <div class="flex items-center space-x-3">
        @if($showBack)
        <x-atoms.icon-button
          variant="ghost"
          size="md"
          href="{{ $backUrl ?: 'javascript:history.back()' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </x-atoms.icon-button>
        @endif

        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
            </svg>
          </div>
          <h1 class="text-xl md:text-2xl font-bold text-neutral-900">
            {{ $title ?: config('app.name') }}
          </h1>
        </div>
      </div>

      <!-- 中央: デスクトップ検索バー -->
      @if($showSearch)
      <div class="hidden md:flex flex-1 max-w-md mx-8">
        <x-atoms.input
          type="search"
          placeholder="店舗名や料理名で検索..."
          :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z\'></path></svg>'" />
      </div>
      @endif

      <!-- 右側: ユーザーアクション -->
      <div class="flex items-center space-x-3">
        @auth
        <x-atoms.button
          variant="primary"
          size="sm"
          href="{{ route('posts.create') }}"
          class="hidden sm:inline-flex">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          投稿作成
        </x-atoms.button>

        <a href="{{ route('profile.show', auth()->user()) }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-neutral-100 transition-colors duration-200">
          <img
            src="{{ auth()->user()->avatar_url ?? '/images/default-avatar.png' }}"
            alt="{{ auth()->user()->name }}"
            class="w-8 h-8 rounded-full object-cover ring-2 ring-primary-200">
          <span class="hidden md:inline text-sm font-medium text-neutral-700">{{ auth()->user()->name }}</span>
        </a>
        @else
        <x-atoms.button variant="ghost" size="sm" href="{{ route('login') }}">
          ログイン
        </x-atoms.button>
        <x-atoms.button variant="primary" size="sm" href="{{ route('register') }}">
          新規登録
        </x-atoms.button>
        @endauth
      </div>
    </div>

    <!-- モバイル検索バー -->
    @if($showSearch)
    <div class="md:hidden mt-4">
      <x-atoms.input
        type="search"
        placeholder="店舗名や料理名で検索..."
        :icon="'<svg class=\'h-5 w-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z\'></path></svg>'" />
    </div>
    @endif
  </div>
</header>