{{-- アイコンボタン: アイコンのみの円形ボタン --}}
@props([
'href' => null,
'type' => 'button',
'disabled' => false
])

@if($href && !$disabled)
<a href="{{ $href }}"
  class="inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
  {{ $slot }}
</a>
@else
<button type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  class="inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
  {{ $slot }}
</button>
@endif