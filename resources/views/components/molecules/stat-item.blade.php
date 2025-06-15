@props([
'value',
'label',
'href' => null,
'color' => 'default'
])

@php
$colorClasses = [
'default' => 'text-neutral-900',
'primary' => 'text-primary-600',
'success' => 'text-success-600',
'warning' => 'text-warning-600',
'error' => 'text-error-600'
];

$valueColor = $colorClasses[$color] ?? $colorClasses['default'];
@endphp

@if($href)
<a href="{{ $href }}" class="text-center hover:bg-neutral-50 rounded-lg p-2 transition-colors duration-200 group">
  <div class="text-lg font-bold {{ $valueColor }} group-hover:text-primary-600 transition-colors duration-200">
    {{ number_format($value) }}
  </div>
  <div class="text-xs text-neutral-500 group-hover:text-neutral-600 transition-colors duration-200">
    {{ $label }}
  </div>
</a>
@else
<div class="text-center p-2">
  <div class="text-lg font-bold {{ $valueColor }}">
    {{ number_format($value) }}
  </div>
  <div class="text-xs text-neutral-500">
    {{ $label }}
  </div>
</div>
@endif