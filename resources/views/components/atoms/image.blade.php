@props([
'src',
'alt' => '',
'aspectRatio' => '16/9',
'rounded' => 'lg',
'lazy' => true
])

@php
$aspectClasses = [
'1/1' => 'aspect-square',
'16/9' => 'aspect-video',
'4/3' => 'aspect-[4/3]',
'3/2' => 'aspect-[3/2]',
];

$roundedClasses = [
'none' => '',
'sm' => 'rounded-sm',
'md' => 'rounded-md',
'lg' => 'rounded-lg',
'xl' => 'rounded-xl',
'full' => 'rounded-full',
];

$aspectClass = $aspectClasses[$aspectRatio] ?? $aspectClasses['16/9'];
$roundedClass = $roundedClasses[$rounded] ?? $roundedClasses['lg'];
@endphp

<div class="{{ $aspectClass }} {{ $roundedClass }} overflow-hidden bg-neutral-200">
  <img
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="w-full h-full object-cover {{ $roundedClass }} transition-transform duration-300 {{ $attributes->get('class') }}"
    {{ $lazy ? 'loading="lazy"' : '' }}
    {{ $attributes->except('class') }} />
</div>