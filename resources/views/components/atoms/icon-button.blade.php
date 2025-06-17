@props([
'variant' => 'ghost',
'size' => 'md',
'disabled' => false,
'href' => null,
'type' => 'button',
'icon' => null
])

@php
$classes = [
'base' => 'inline-flex items-center justify-center rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2',

// サイズ
'sm' => 'w-8 h-8 text-sm',
'md' => 'w-10 h-10 text-base',
'lg' => 'w-12 h-12 text-lg',

// バリアント
'ghost' => 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-100 focus:ring-neutral-500',
'primary' => 'bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 shadow-md hover:shadow-lg',
'secondary' => 'bg-white text-neutral-700 border border-neutral-300 hover:bg-neutral-50 focus:ring-neutral-500',

// 無効状態
'disabled' => 'opacity-50 cursor-not-allowed pointer-events-none'
];

$buttonClasses = collect([
$classes['base'],
$classes[$size] ?? $classes['md'],
$classes[$variant] ?? $classes['ghost'],
$disabled ? $classes['disabled'] : ''
])->implode(' ');
@endphp

@if($href && !$disabled)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
  @if($icon)
  {!! $icon !!}
  @elseif(isset($slot) && !$slot->isEmpty())
  {{ $slot }}
  @else
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
  </svg>
  @endif
</a>
@else
<button
  type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  {{ $attributes->merge(['class' => $buttonClasses]) }}>
  @if($icon)
  {!! $icon !!}
  @elseif(isset($slot) && !$slot->isEmpty())
  {{ $slot }}
  @else
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
  </svg>
  @endif
</button>
@endif