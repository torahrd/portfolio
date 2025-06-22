{{--
  店舗カード用アクションボタンコンポーネント
  props:
    - shop: 店舗データ
    - showFavorite: お気に入りボタン表示（デフォルトtrue）
    - showShare: シェアボタン表示（デフォルトtrue）
--}}
@props([
'shop',
'showFavorite' => true,
'showShare' => true,
])

<div class="flex items-center space-x-2">
  @if($showFavorite)
  <!-- 星型アイコン（お気に入り） -->
  <x-atoms.button-icon size="sm" aria-label="お気に入り">
    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
    </svg>
  </x-atoms.button-icon>
  @endif
  @if($showShare)
  <!-- シェアアイコン -->
  <x-atoms.button-icon size="sm" aria-label="シェア">
    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
    </svg>
  </x-atoms.button-icon>
  @endif
</div>