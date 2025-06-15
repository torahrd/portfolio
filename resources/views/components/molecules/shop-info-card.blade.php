@props([
'shop',
'showFavoriteButton' => true,
'compact' => false
])

<div class="bg-white rounded-xl shadow-card p-4 {{ $compact ? 'space-y-3' : 'space-y-4' }}">
  <!-- ヘッダー -->
  <div class="flex items-start justify-between">
    <div class="flex-1">
      <h3 class="text-lg font-semibold text-neutral-900 mb-1">
        {{ $shop->name }}
      </h3>

      <!-- 営業ステータス -->
      @if(isset($shop->is_open_now))
      <x-atoms.badge
        :variant="$shop->is_open_now ? 'open' : 'closed'"
        :icon="$shop->is_open_now ? '🟢' : '🔴'">
        {{ $shop->is_open_now ? '営業中' : '営業時間外' }}
      </x-atoms.badge>
      @else
      <x-atoms.badge variant="unknown" icon="❓">
        営業時間不明
      </x-atoms.badge>
      @endif
    </div>

    @if($showFavoriteButton)
    <x-atoms.icon-button
      variant="favorite"
      size="md"
      data-shop-id="{{ $shop->id }}"
      class="favorite-btn">
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
      </svg>
    </x-atoms.icon-button>
    @endif
  </div>

  <!-- 基本情報 -->
  <div class="space-y-2 text-sm text-neutral-600">
    <!-- 住所 -->
    <div class="flex items-start space-x-2">
      <svg class="w-4 h-4 mt-0.5 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
      </svg>
      <span class="flex-1">{{ $shop->address }}</span>
    </div>

    <!-- 平均予算 -->
    @if($shop->average_budget)
    <div class="flex items-center space-x-2">
      <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
      </svg>
      <span>平均予算: {{ $shop->formatted_average_budget }}</span>
    </div>
    @endif

    <!-- お気に入り数 -->
    <div class="flex items-center space-x-2">
      <svg class="w-4 h-4 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
      </svg>
      <span><span id="favorites-count-{{ $shop->id }}">{{ $shop->favorites_count ?? 0 }}</span>人がお気に入り</span>
    </div>
  </div>

  @if(!$compact)
  <!-- アクションボタン -->
  <div class="flex space-x-2 pt-2 border-t border-neutral-100">
    <x-atoms.button
      variant="primary"
      size="sm"
      href="{{ route('shops.show', $shop) }}"
      class="flex-1">
      詳細を見る
    </x-atoms.button>

    @if($shop->reservation_url)
    <x-atoms.button
      variant="secondary"
      size="sm"
      href="{{ $shop->reservation_url }}"
      target="_blank"
      class="flex-1">
      予約する
    </x-atoms.button>
    @endif
  </div>
  @endif
</div>