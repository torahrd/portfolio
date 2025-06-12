@props([
'header' => null,
'footer' => null,
'padding' => true,
'shadow' => 'md'
])

@php
$cardClasses = 'card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700';

$shadowClasses = [
'none' => '',
'sm' => 'shadow-sm',
'md' => 'shadow-md',
'lg' => 'shadow-lg',
'xl' => 'shadow-xl',
];

$cardClasses .= ' ' . $shadowClasses[$shadow];

if (!$padding) {
$cardClasses .= ' p-0';
} else {
$cardClasses .= ' p-6';
}
@endphp

<div class="{{ $cardClasses }}" {{ $attributes }}>
  @if($header)
  <div class="card-header">
    {{ $header }}
  </div>
  @endif

  <div class="card-body {{ !$padding ? 'p-6' : '' }}">
    {{ $slot }}
  </div>

  @if($footer)
  <div class="card-footer">
    {{ $footer }}
  </div>
  @endif
</div>