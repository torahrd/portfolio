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
    <x-molecules.shop-actions :shop="$shop"></x-molecules.shop-actions>
    @endif
  </div>

  <!-- 店舗情報: 住所・電話・営業時間 -->
  <div class="space-y-3 text-sm text-neutral-600">
    @if($shop->address)
    <x-molecules.shop-info-row>
      <x-slot:icon>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
      </x-slot:icon>
      <span>{{ $shop->address }}</span>
    </x-molecules.shop-info-row>
    @endif

    @if($shop->phone)
    <x-molecules.shop-info-row>
      <x-slot:icon>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
        </svg>
      </x-slot:icon>
      <span>{{ $shop->phone }}</span>
    </x-molecules.shop-info-row>
    @endif

    @if($shop->operating_hours)
    <x-molecules.shop-info-row>
      <x-slot:icon>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </x-slot:icon>
      <span>{{ $shop->operating_hours }}</span>
      @if($shop->is_open)
      <x-atoms.badge variant="open" size="xs" class="ml-2">営業中</x-atoms.badge>
      @else
      <x-atoms.badge variant="closed" size="xs" class="ml-2">営業時間外</x-atoms.badge>
      @endif
    </x-molecules.shop-info-row>
    @endif
  </div>

  <!-- 評価とレビュー数 -->
  {{-- 評価機能は個人の好み重視のため除外 --}}
</div>