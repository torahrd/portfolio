@props([
'variant' => 'primary',
'size' => 'md',
'type' => 'button',
'disabled' => false,
'loading' => false,
'icon' => null,
'href' => null,
'target' => null
])

@php
$baseClasses = 'btn focus-ring transition-all duration-200 inline-flex items-center justify-center font-medium';

$variants = [
'primary' => 'btn-primary',
'secondary' => 'btn-secondary',
'success' => 'btn-success',
'warning' => 'btn-warning',
'danger' => 'btn-danger',
'outline-primary' => 'btn-outline-primary',
'outline-secondary' => 'btn-outline-secondary',
'outline-success' => 'btn-outline-success',
'outline-warning' => 'btn-outline-warning',
'outline-danger' => 'btn-outline-danger',
];

$sizes = [
'sm' => 'px-3 py-1.5 text-sm',
'md' => 'px-4 py-2 text-sm',
'lg' => 'px-6 py-3 text-base',
'xl' => 'px-8 py-4 text-lg',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];

if ($disabled || $loading) {
$classes .= ' opacity-50 cursor-not-allowed';
}
@endphp

@if($href)
<a href="{{ $href }}"
  @if($target) target="{{ $target }}" @endif
  class="{{ $classes }}"
  @if($disabled) tabindex="-1" aria-disabled="true" @endif
  {{ $attributes }}>
  @if($loading)
  <i class="fas fa-spinner fa-spin mr-2"></i>
  @elseif($icon)
  <i class="{{ $icon }} mr-2"></i>
  @endif
  {{ $slot }}
</a>
@else
<button type="{{ $type }}"
  class="{{ $classes }}"
  @if($disabled || $loading) disabled @endif
  {{ $attributes }}>
  @if($loading)
  <i class="fas fa-spinner fa-spin mr-2"></i>
  @elseif($icon)
  <i class="{{ $icon }} mr-2"></i>
  @endif
  {{ $slot }}
</button>
@endif