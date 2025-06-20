{{-- 店舗カードコンポーネント
  props:
    - shop: 店舗データ（必須）
    - showActions: アクションボタン表示（デフォルトtrue）
--}}

@props([
'shop',
'showActions' => true,
])

<div class="bg-white rounded-xl shadow-card p-6 hover:shadow-card-hover transition-shadow duration-300">
  <!-- ヘッダー: 店名とカテゴリ -->
  <div class="flex items-start justify-between mb-4">
    <div class="flex-1">
      <h3 class="text-xl font-bold text-neutral-900 mb-2">
        <a href="{{ route('shops.show', $shop) }}" class="hover:text-primary-500 transition-colors duration-200">
          {{ $shop->name }}
        </a>
      </h3>

      @if($shop->category)
      <x-atoms.badge-info size="sm">
        {{ $shop->category }}
      </x-atoms.badge-info>
      @endif
    </div>

    @if($showActions)
    <div class="flex items-center space-x-2">
      <!-- 星型アイコン（お気に入り） -->
      <x-atoms.button-icon size="sm" aria-label="お気に入り">
        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
      </x-atoms.button-icon>
      <!-- シェアアイコン -->
      <x-atoms.button-icon size="sm" aria-label="シェア">
        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
        </svg>
      </x-atoms.button-icon>
    </div>
    @endif
  </div>

  <!-- 店舗情報: 住所・電話・営業時間 -->
  <div class="space-y-3 text-sm text-neutral-600">
    @if($shop->address)
    <div class="flex items-start space-x-2">
      <svg class="w-4 h-4 mt-0.5 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
      </svg>
      <span>{{ $shop->address }}</span>
    </div>
    @endif

    @if($shop->phone)
    <div class="flex items-center space-x-2">
      <svg class="w-4 h-4 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
      </svg>
      <span>{{ $shop->phone }}</span>
    </div>
    @endif

    @if($shop->operating_hours)
    <div class="flex items-start space-x-2">
      <svg class="w-4 h-4 mt-0.5 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <div>
        <span>{{ $shop->operating_hours }}</span>
        @if($shop->is_open)
        <x-atoms.badge variant="open" size="xs" class="ml-2">営業中</x-atoms.badge>
        @else
        <x-atoms.badge variant="closed" size="xs" class="ml-2">営業時間外</x-atoms.badge>
        @endif
      </div>
    </div>
    @endif
  </div>

  <!-- 評価とレビュー数 -->
  @if($shop->rating || $shop->reviews_count)
  <div class="flex items-center justify-between mt-4 pt-4 border-t border-neutral-200">
    @if($shop->rating)
    <x-atoms.rating :rating="$shop->rating" size="sm" />
    @endif

    @if($shop->reviews_count)
    <span class="text-sm text-neutral-500">
      {{ $shop->reviews_count }}件のレビュー
    </span>
    @endif
  </div>
  @endif
</div>