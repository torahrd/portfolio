@props([
'variant' => 'default',
'size' => 'sm',
'removable' => false
])

@php
$classes = [
'base' => 'inline-flex items-center font-medium rounded-full',

// サイズ
'xs' => 'px-2 py-0.5 text-xs',
'sm' => 'px-2.5 py-1 text-sm',
'md' => 'px-3 py-1.5 text-sm',

// バリアント
'default' => 'bg-neutral-100 text-neutral-800',
'primary' => 'bg-primary-100 text-primary-800',
'success' => 'bg-success-100 text-success-800',
'warning' => 'bg-warning-100 text-warning-800',
'error' => 'bg-error-100 text-error-800',
'info' => 'bg-sage-100 text-sage-800',
'open' => 'bg-success-100 text-success-800',
'closed' => 'bg-error-100 text-error-800'
];

$badgeClasses = collect([
$classes['base'],
$classes[$size] ?? $classes['sm'],
$classes[$variant] ?? $classes['default']
])->implode(' ');
@endphp

<span {{ $attributes->merge(['class' => $badgeClasses]) }}>
  {{ $slot }}

  @if($removable)
  <button type="button" class="ml-1.5 -mr-1 hover:bg-current hover:bg-opacity-20 rounded-full p-0.5 transition-colors duration-200">
    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
    </svg>
  </button>
  @endif
</span>