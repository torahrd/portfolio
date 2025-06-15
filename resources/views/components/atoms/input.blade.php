@props([
'type' => 'text',
'disabled' => false,
'error' => false,
'placeholder' => ''
])

@php
$classes = [
'base' => 'block w-full rounded-lg border transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2',
'normal' => 'border-neutral-300 focus:border-primary-500 focus:ring-primary-500 bg-white text-neutral-900 placeholder-neutral-500',
'error' => 'border-error-300 focus:border-error-500 focus:ring-error-500 bg-error-50 text-error-900 placeholder-error-500',
'disabled' => 'bg-neutral-100 text-neutral-500 cursor-not-allowed'
];

$inputClasses = collect([
$classes['base'],
$error ? $classes['error'] : $classes['normal'],
$disabled ? $classes['disabled'] : '',
'px-4 py-3 text-base'
])->implode(' ');
@endphp

<input
  type="{{ $type }}"
  {{ $disabled ? 'disabled' : '' }}
  placeholder="{{ $placeholder }}"
  {{ $attributes->merge(['class' => $inputClasses]) }} />