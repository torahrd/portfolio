@props([
'src' => null,
'alt' => 'User Avatar',
'size' => 'md',
'online' => false
])

@php
$sizes = [
'xs' => 'w-6 h-6',
'sm' => 'w-8 h-8',
'md' => 'w-10 h-10',
'lg' => 'w-12 h-12',
'xl' => 'w-16 h-16',
'2xl' => 'w-20 h-20',
];

$avatarClasses = [
'relative inline-block rounded-full overflow-hidden bg-neutral-200',
$sizes[$size] ?? $sizes['md']
];
@endphp

<div {{ $attributes->merge(['class' => implode(' ', $avatarClasses)]) }}>
  @if($src)
  <img src="{{ $src }}" alt="{{ $alt }}" class="w-full h-full object-cover">
  @else
  <div class="w-full h-full flex items-center justify-center bg-neutral-300 text-neutral-600">
    <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
    </svg>
  </div>
  @endif

  @if($online)
  <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
  @endif
</div>