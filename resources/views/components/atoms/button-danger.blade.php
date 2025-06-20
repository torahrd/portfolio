{{-- 危険ボタン: 削除などの破壊的操作用 --}}
@props([
'type' => 'button',
'disabled' => false
])

<button type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
  {{ $slot }}
</button>