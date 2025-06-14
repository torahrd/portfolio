@props([
'variant' => 'primary',
'size' => 'base',
'disabled' => false,
'href' => null,
'type' => 'button'
])

@php
$classes = collect([
'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2',

// サイズ設定
'sm' => 'px-3 py-1.5 text-sm',
'base' => 'px-4 py-2 text-base',
'lg' => 'px-6 py-3 text-lg',

// バリアント設定
'primary' => 'bg-primary-500 hover:bg-primary-600 text-white focus:ring-primary-500 shadow-md hover:shadow-lg transform hover:scale-105',
'secondary' => 'bg-white hover:bg-neutral-50 text-neutral-700 border-2 border-neutral-200 hover:border-neutral-300 focus:ring-neutral-500',
'ghost' => 'bg-transparent hover:bg-neutral-100 text-neutral-600 hover:text-neutral-700 focus:ring-neutral-500',

// 無効状態
'disabled' => 'opacity-50 cursor-not-allowed pointer-events-none'
]);

$buttonClasses = $classes->only(['base'])->merge([
$classes[$size] ?? $classes['base'],
$classes[$variant] ?? $classes['primary'],
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