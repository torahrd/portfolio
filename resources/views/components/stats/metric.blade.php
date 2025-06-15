@props([
'label',
'value',
'change' => null,
'changeType' => 'neutral',
'icon' => null,
'href' => null
])

@php
$changeColors = [
'positive' => 'text-green-600',
'negative' => 'text-red-600',
'neutral' => 'text-gray-600',
];

$changeColor = $changeColors[$changeType] ?? $changeColors['neutral'];
@endphp

@if($href)
<a href="{{ $href }}" class="block" {{ $attributes }}>
  @endif

  <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700 {{ $href ? 'hover:shadow-md transition-shadow duration-200' : '' }}">
    <div class="flex items-center justify-between">
      <div class="flex-1">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>

        @if($change)
        <p class="text-sm {{ $changeColor }} flex items-center mt-1">
          @if($changeType === 'positive')
          <i class="fas fa-arrow-up mr-1"></i>
          @elseif($changeType === 'negative')
          <i class="fas fa-arrow-down mr-1"></i>
          @endif
          {{ $change }}
        </p>
        @endif
      </div>

      @if($icon)
      <div class="flex-shrink-0">
        <i class="{{ $icon }} text-3xl text-gray-400"></i>
      </div>
      @endif
    </div>
  </div>

  @if($href)
</a>
@endif