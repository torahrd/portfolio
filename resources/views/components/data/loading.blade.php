@props([
'type' => 'spinner',
'size' => 'md',
'text' => '読み込み中...'
])

@php
$sizes = [
'sm' => 'w-4 h-4',
'md' => 'w-8 h-8',
'lg' => 'w-12 h-12',
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="flex items-center justify-center py-8" {{ $attributes }}>
  @if($type === 'spinner')
  <div class="flex flex-col items-center space-y-3">
    <div class="{{ $sizeClass }} border-4 border-gray-200 border-t-primary-600 rounded-full animate-spin"></div>
    @if($text)
    <span class="text-gray-600 text-sm">{{ $text }}</span>
    @endif
  </div>
  @elseif($type === 'dots')
  <div class="flex items-center space-x-2">
    <div class="w-2 h-2 bg-primary-600 rounded-full animate-bounce"></div>
    <div class="w-2 h-2 bg-primary-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
    <div class="w-2 h-2 bg-primary-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
    @if($text)
    <span class="ml-3 text-gray-600 text-sm">{{ $text }}</span>
    @endif
  </div>
  @elseif($type === 'pulse')
  <div class="flex items-center space-x-3">
    <div class="{{ $sizeClass }} bg-primary-600 rounded-full animate-pulse"></div>
    @if($text)
    <span class="text-gray-600 text-sm">{{ $text }}</span>
    @endif
  </div>
  @endif
</div>