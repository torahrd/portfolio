@props([
'showSearch' => true,
'transparent' => false
])

<header class="sticky top-0 z-50 transition-all duration-300 {{ $transparent ? 'bg-transparent' : 'bg-white border-b border-neutral-200' }} backdrop-blur-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- ロゴ -->
      <div class="flex items-center">
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-lg">T+</span>
          </div>
          <span class="hidden sm:block text-xl font-bold text-neutral-900">TasteRetreat</span>
        </a>
      </div>

      <!-- 検索バー（デスクトップ） -->
      @if($showSearch)
      <div class="hidden md:block flex-1 max-w-2xl mx-8">
        <x-molecules.search-bar />
      </div>
      @endif

      <!-- ナビゲーション -->
      <div class="flex items-center space-x-4">
        @auth
        <!-- 通知（リンク型・アイコン＋テキスト） -->
        <div class="relative">
          <a href="{{ route('notifications.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 text-primary-600 font-semibold transition-colors relative">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 17h5l-5 5v-5z"></path>
            </svg>
            通知
            @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ min(auth()->user()->unreadNotifications->count(), 99) }}
            </span>
            @endif
          </a>
        </div>

        <!-- 投稿（リンク型・アイコン＋テキスト） -->
        <a href="{{ route('posts.create') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 text-primary-600 font-semibold transition-colors">
          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          投稿
        </a>

        <!-- 地図ページへのリンク -->
        <a href="{{ route('map.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-primary-50 text-primary-600 font-semibold transition-colors">
          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 0 011.553-1.946l5.894-1.684a2 2 0 011.106 0l5.894 1.684A2 2 0 0121 6.618v8.764a2 2 0 01-1.553 1.946L15 20m-6 0V4m6 16V4" />
          </svg>
          地図
        </a>

        <!-- ユーザーメニュー -->
        <div class="relative" x-data="{ open: false }">
          <button
            x-on:click="open = !open"
            class="flex items-center space-x-2 p-1 rounded-full hover:bg-neutral-100 transition-colors duration-200">
            <x-atoms.avatar
              :user="auth()->user()"
              size="sm" />
            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>

          <!-- ユーザーメニュードロップダウン -->
          <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-on:click.outside="open = false"
            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-neutral-200 py-1 z-50">
            <a href="{{ route('profile.show', auth()->user()) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors duration-200">
              プロフィール
            </a>
            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors duration-200">
              設定
            </a>
            <hr class="my-1 border-neutral-200">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors duration-200">
                ログアウト
              </button>
            </form>
          </div>
        </div>
        @else
        <!-- ゲストユーザー -->
        <div class="flex items-center space-x-3">
          <x-atoms.button-secondary href="{{ route('login') }}" size="sm">
            ログイン
          </x-atoms.button-secondary>
          <x-atoms.button-primary href="{{ route('register') }}" size="sm">
            新規登録
          </x-atoms.button-primary>
        </div>
        @endauth
      </div>
    </div>

    <!-- モバイル検索バー -->
    @if($showSearch)
    <div class="md:hidden pb-4">
      <x-molecules.search-bar />
    </div>
    @endif
  </div>
</header>