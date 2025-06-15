@props([
'src' => null,
'alt' => '',
'size' => 'md',
'initials' => null,
'online' => false
])

@php
$sizes = [
'xs' => 'w-6 h-6 text-xs',
'sm' => 'w-8 h-8 text-sm',
'md' => 'w-10 h-10 text-base',
'lg' => 'w-12 h-12 text-lg',
'xl' => 'w-16 h-16 text-xl',
'2xl' => 'w-20 h-20 text-2xl'
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="relative inline-block {{ $sizeClass }}">
  @if($src)
  <img
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="rounded-full object-cover {{ $sizeClass }} ring-2 ring-white shadow-sm"
    loading="lazy" />
  @elseif($initials)
  <div class="flex items-center justify-center {{ $sizeClass }} rounded-full bg-primary-500 text-white font-medium">
    {{ $initials }}
  </div>
  @else
  <div class="flex items-center justify-center {{ $sizeClass }} rounded-full bg-neutral-300 text-neutral-600">
    <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
    </svg>
  </div>
  @endif

  @if($online)
  <span class="absolute bottom-0 right-0 block {{ $size === 'xs' ? 'h-2 w-2' : ($size === 'sm' ? 'h-2.5 w-2.5' : 'h-3 w-3') }} rounded-full bg-success-400 ring-2 ring-white"></span>
  @endif
</div>