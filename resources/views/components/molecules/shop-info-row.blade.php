{{--
  店舗情報行コンポーネント
  - icon: アイコン用スロット
  - slot: コンテンツ用スロット
--}}
<div {{ $attributes->merge(['class' => 'flex items-start space-x-2']) }}>
  <div class="w-4 h-4 mt-0.5 text-neutral-400 flex-shrink-0">
    {{ $icon }}
  </div>
  <div class="flex-1 min-w-0">
    {{ $slot }}
  </div>
</div>