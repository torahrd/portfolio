{{-- resources/views/components/atoms/button.blade.php --}}

@props([
'variant' => 'primary',
'size' => 'md',
'disabled' => false,
'href' => null,
'type' => 'button',
'icon' => null
])

@php
$classes = [
'base' => 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 border',

// サイズ
'xs' => 'px-2.5 py-1.5 text-xs min-h-[32px]',
'sm' => 'px-3 py-2 text-sm min-h-[36px]',
'md' => 'px-4 py-2.5 text-sm min-h-[40px]',
'lg' => 'px-5 py-3 text-base min-h-[44px]',
'xl' => 'px-6 py-3.5 text-lg min-h-[48px]',

// バリアント
'primary' => 'bg-primary-500 text-white border-primary-500 hover:bg-primary-600 hover:border-primary-600 focus:ring-primary-500 shadow-sm hover:shadow-md',
'secondary' => 'bg-white text-neutral-700 border-neutral-300 hover:bg-neutral-50 hover:border-neutral-400 focus:ring-neutral-500 shadow-sm',
'ghost' => 'bg-transparent text-neutral-600 border-transparent hover:text-neutral-900 hover:bg-neutral-100 focus:ring-neutral-500',
'danger' => 'bg-red-500 text-white border-red-500 hover:bg-red-600 hover:border-red-600 focus:ring-red-500 shadow-sm hover:shadow-md',
'warning' => 'bg-yellow-500 text-white border-yellow-500 hover:bg-yellow-600 hover:border-yellow-600 focus:ring-yellow-500 shadow-sm hover:shadow-md',
'success' => 'bg-green-500 text-white border-green-500 hover:bg-green-600 hover:border-green-600 focus:ring-green-500 shadow-sm hover:shadow-md',

// 無効状態
'disabled' => 'opacity-50 cursor-not-allowed pointer-events-none'
];

$buttonClasses = collect([
$classes['base'],
$classes[$size] ?? $classes['md'],
$classes[$variant] ?? $classes['primary'],
$disabled ? $classes['disabled'] : ''
])->filter()->implode(' ');
@endphp

@if($href && !$disabled)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
  @if($icon)
  <span class="flex-shrink-0 mr-2">
    {!! $icon !!}
  </span>
  @endif

  @if(isset($slot) && method_exists($slot, 'isEmpty') && !$slot->isEmpty())
  {{ $slot }}
  @elseif(isset($slot) && !empty(trim($slot)))
  {{ $slot }}
  @else
  Button
  @endif
</a>
@else
<button
  type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  {{ $attributes->merge(['class' => $buttonClasses]) }}>

  @if($icon)
  <span class="flex-shrink-0 mr-2">
    {!! $icon !!}
  </span>
  @endif

  @if(isset($slot) && method_exists($slot, 'isEmpty') && !$slot->isEmpty())
  {{ $slot }}
  @elseif(isset($slot) && !empty(trim($slot)))
  {{ $slot }}
  @else
  Button
  @endif
</button>
@endif