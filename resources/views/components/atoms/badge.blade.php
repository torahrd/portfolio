@props([
'variant' => 'default',
'size' => 'md',
'icon' => null,
'removable' => false
])

@php
$classes = collect([
'inline-flex items-center rounded-full font-medium transition-all duration-200',

// サイズ設定
'sm' => 'px-2 py-0.5 text-xs',
'md' => 'px-2.5 py-1 text-sm',
'lg' => 'px-3 py-1.5 text-base',

// バリアント設定
'default' => 'bg-neutral-100 text-neutral-800',
'primary' => 'bg-primary-100 text-primary-800',
'success' => 'bg-success-100 text-success-800',
'warning' => 'bg-warning-100 text-warning-800',
'error' => 'bg-error-100 text-error-800',
'open' => 'bg-success-100 text-success-800',
'closed' => 'bg-error-100 text-error-800',
'unknown' => 'bg-neutral-100 text-neutral-800',
'visited' => 'bg-success-100 text-success-800',
'planned' => 'bg-warning-100 text-warning-800'
]);

$badgeClasses = $classes->only(['inline-flex'])->merge([
$classes[$size] ?? $classes['md'],
$classes[$variant] ?? $classes['default']
])->implode(' ');
@endphp

<span {{ $attributes->merge(['class' => $badgeClasses]) }}>
  @if($icon)
  <span class="mr-1">{{ $icon }}</span>
  @endif

  {{ $slot }}

  @if($removable)
  <button type="button" class="ml-1 hover:text-neutral-600 transition-colors duration-200">
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>
  </button>
  @endif
</span>