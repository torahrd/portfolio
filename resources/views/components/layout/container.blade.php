@props([
'size' => 'default',
'padding' => true
])

@php
$containerClasses = 'mx-auto';

$sizes = [
'sm' => 'max-w-3xl',
'default' => 'max-w-7xl',
'lg' => 'max-w-screen-xl',
'xl' => 'max-w-screen-2xl',
'full' => 'max-w-full',
];

$containerClasses .= ' ' . $sizes[$size];

if ($padding) {
$containerClasses .= ' px-4 sm:px-6 lg:px-8';
}
@endphp

<div class="{{ $containerClasses }}" {{ $attributes }}>
  {{ $slot }}
</div>