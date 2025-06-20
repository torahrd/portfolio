{{-- プライマリボタン: 主要アクション用 --}}
@props([
'href' => null,
'type' => 'button',
'disabled' => false
])

@if($href && !$disabled)
<a href="{{ $href }}"
  class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
  {{ $slot }}
</a>
@else
<button type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
  {{ $slot }}
</button>
@endif