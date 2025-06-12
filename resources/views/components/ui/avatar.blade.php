@props([
'src' => null,
'alt' => '',
'size' => 'md',
'fallback' => null,
'status' => null
])

@php
$sizes = [
'xs' => 'w-6 h-6',
'sm' => 'w-8 h-8',
'md' => 'w-12 h-12',
'lg' => 'w-16 h-16',
'xl' => 'w-24 h-24',
'2xl' => 'w-32 h-32',
];

$sizeClass = $sizes[$size] ?? $sizes['md'];

$statusSizes = [
'xs' => 'w-1.5 h-1.5',
'sm' => 'w-2 h-2',
'md' => 'w-3 h-3',
'lg' => 'w-4 h-4',
'xl' => 'w-6 h-6',
'2xl' => 'w-8 h-8',
];

$statusSizeClass = $statusSizes[$size] ?? $statusSizes['md'];

$statusColors = [
'online' => 'bg-green-500',
'busy' => 'bg-red-500',
'away' => 'bg-yellow-500',
'offline' => 'bg-gray-400',
];
@endphp

<div class="relative inline-block" {{ $attributes }}>
  @if($src)
  <img src="{{ $src }}"
    alt="{{ $alt }}"
    class="avatar {{ $sizeClass }} rounded-full object-cover">
  @else
  <div class="avatar {{ $sizeClass }} rounded-full bg-gray-300 flex items-center justify-center">
    @if($fallback)
    <span class="text-gray-600 font-medium">{{ $fallback }}</span>
    @else
    <i class="fas fa-user text-gray-600"></i>
    @endif
  </div>
  @endif

  @if($status)
  <span class="absolute bottom-0 right-0 block {{ $statusSizeClass }} rounded-full ring-2 ring-white {{ $statusColors[$status] ?? $statusColors['offline'] }}"></span>
  @endif
</div>