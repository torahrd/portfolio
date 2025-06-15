@props([
'variant' => 'default',
'size' => 'md',
'active' => false,
'disabled' => false,
'href' => null,
'type' => 'button'
])

@php
$classes = collect([
'inline-flex items-center justify-center rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2',

// サイズ設定
'sm' => 'w-8 h-8 text-sm',
'md' => 'w-10 h-10 text-base',
'lg' => 'w-12 h-12 text-lg',

// バリアント設定
'default' => 'bg-neutral-100 hover:bg-neutral-200 text-neutral-600 hover:text-neutral-700 focus:ring-neutral-500',
'primary' => 'bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 focus:ring-primary-500',
'ghost' => 'bg-transparent hover:bg-neutral-100 text-neutral-600 hover:text-neutral-700 focus:ring-neutral-500',
'favorite' => 'bg-transparent hover:bg-red-50 text-neutral-400 hover:text-red-500 focus:ring-red-500',

// アクティブ状態
'active-primary' => 'bg-primary-500 text-white hover:bg-primary-600',
'active-favorite' => 'bg-red-50 text-red-500',

// 無効状態
'disabled' => 'opacity-50 cursor-not-allowed pointer-events-none'
]);

$buttonClasses = $classes->only(['inline-flex'])->merge([
$classes[$size] ?? $classes['md'],
$active ? ($classes["active-{$variant}"] ?? $classes['active-primary']) : ($classes[$variant] ?? $classes['default']),
$disabled ? $classes['disabled'] : ''
])->implode(' ');
@endphp

@if($href && !$disabled)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
  {{ $slot }}
</a>
@else
<button
  type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  {{ $attributes->merge(['class' => $buttonClasses]) }}>
  {{ $slot }}
</button>
@endif