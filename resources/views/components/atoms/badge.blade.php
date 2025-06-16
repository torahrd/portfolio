@props([
'variant' => 'default',
'size' => 'md'
])

@php
$variants = [
'default' => 'bg-neutral-100 text-neutral-700',
'primary' => 'bg-primary-100 text-primary-700',
'success' => 'bg-green-100 text-green-700',
'warning' => 'bg-yellow-100 text-yellow-700',
'error' => 'bg-red-100 text-red-700',
'info' => 'bg-blue-100 text-blue-700',
'open' => 'bg-green-100 text-green-700',
'closed' => 'bg-red-100 text-red-700',
];

$sizes = [
'xs' => 'px-2 py-1 text-xs',
'sm' => 'px-2.5 py-1.5 text-xs',
'md' => 'px-3 py-1.5 text-sm',
'lg' => 'px-4 py-2 text-base',
];

$classes = [
'inline-flex items-center font-medium rounded-full',
$variants[$variant] ?? $variants['default'],
$sizes[$size] ?? $sizes['md']
];
@endphp

<span {{ $attributes->merge(['class' => implode(' ', $classes)]) }}>
  {{ $slot }}
</span>