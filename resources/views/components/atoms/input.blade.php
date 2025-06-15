@props([
'type' => 'text',
'size' => 'md',
'variant' => 'default',
'error' => false,
'disabled' => false,
'placeholder' => '',
'icon' => null,
'iconPosition' => 'left'
])

@php
$classes = collect([
'block w-full rounded-lg border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0',

// サイズ設定
'sm' => 'px-3 py-1.5 text-sm',
'md' => 'px-4 py-2 text-base',
'lg' => 'px-4 py-3 text-lg',

// バリアント設定
'default' => 'border-neutral-300 focus:border-primary-500 focus:ring-primary-500',
'error' => 'border-error-300 focus:border-error-500 focus:ring-error-500',

// 無効状態
'disabled' => 'bg-neutral-100 text-neutral-500 cursor-not-allowed'
]);

$inputClasses = $classes->only(['block'])->merge([
$classes[$size] ?? $classes['md'],
$error ? $classes['error'] : $classes['default'],
$disabled ? $classes['disabled'] : 'bg-white',
$icon ? ($iconPosition === 'left' ? 'pl-10' : 'pr-10') : ''
])->implode(' ');
@endphp

<div class="relative">
  @if($icon)
  <div class="absolute inset-y-0 {{ $iconPosition === 'left' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
    <div class="h-5 w-5 text-neutral-400">
      {{ $icon }}
    </div>
  </div>
  @endif

  <input
    type="{{ $type }}"
    placeholder="{{ $placeholder }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $inputClasses]) }}>
</div>