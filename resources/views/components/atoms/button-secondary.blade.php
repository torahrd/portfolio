{{-- セカンダリボタン: 補助アクション用 --}}
@props([
'href' => null,
'type' => 'button',
'disabled' => false
])

@if($href && !$disabled)
<a href="{{ $href }}"
  class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
  {{ $slot }}
</a>
@else
<button type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
  {{ $slot }}
</button>
@endif