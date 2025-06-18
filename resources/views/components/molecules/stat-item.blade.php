{{-- resources/views/components/molecules/stat-item.blade.php --}}

@props([
'title',
'value',
'icon' => null,
'href' => null,
'variant' => 'default'
])

@php
$variants = [
'default' => 'bg-white hover:bg-neutral-50',
'primary' => 'bg-primary-50 hover:bg-primary-100',
'success' => 'bg-green-50 hover:bg-green-100',
'warning' => 'bg-yellow-50 hover:bg-yellow-100',
'error' => 'bg-red-50 hover:bg-red-100',
];

$classes = [
'bg-white rounded-xl shadow-card p-6 transition-all duration-300',
$variants[$variant] ?? $variants['default'],
$href ? 'hover:shadow-card-hover cursor-pointer' : ''
];

$containerClasses = implode(' ', array_filter($classes));
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $containerClasses]) }}>
  <div class="flex items-center justify-between">
    <div class="flex-1">
      <div class="flex items-center space-x-3 mb-2">
        @if($icon)
        <div class="flex-shrink-0">
          {!! $icon !!}
        </div>
        @endif
        <h3 class="text-sm font-medium text-neutral-600">{{ $title }}</h3>
      </div>
      <p class="text-3xl font-bold text-neutral-900">{{ is_numeric($value) ? number_format($value) : $value }}</p>
    </div>

    <div class="flex-shrink-0 ml-4">
      <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </div>
  </div>
</a>
@else
<div {{ $attributes->merge(['class' => $containerClasses]) }}>
  <div class="flex items-center space-x-3 mb-2">
    @if($icon)
    <div class="flex-shrink-0">
      {!! $icon !!}
    </div>
    @endif
    <h3 class="text-sm font-medium text-neutral-600">{{ $title }}</h3>
  </div>
  <p class="text-3xl font-bold text-neutral-900">{{ is_numeric($value) ? number_format($value) : $value }}</p>
</div>
@endif