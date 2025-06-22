@props([
'user',
'size' => 'default', // 'small', 'default', 'large'
'online' => false
])

@php
$sizeClasses = [
'small' => 'w-8 h-8',
'default' => 'w-10 h-10',
'large' => 'w-12 h-12',
];

$fontSizeClasses = [
'small' => 'text-sm',
'default' => 'text-lg',
'large' => 'text-xl',
];

$avatarSize = $sizeClasses[$size] ?? $sizeClasses['default'];
$fontSize = $fontSizeClasses[$size] ?? $fontSizeClasses['default'];

$avatarClasses = [
'relative inline-block rounded-full overflow-hidden bg-neutral-200',
$avatarSize
];
@endphp

<div {{ $attributes->merge(['class' => implode(' ', $avatarClasses)]) }}>
  <a href="{{ route('profile.show', $user) }}" class="block w-full h-full">
    @if ($user->avatar_url)
    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
    @else
    <div class="w-full h-full bg-neutral-200 flex items-center justify-center">
      <span class="font-bold text-neutral-500 {{ $fontSize }}">
        {{ strtoupper(substr($user->name, 0, 1)) }}
      </span>
    </div>
    @endif
  </a>

  @if($online)
  <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
  @endif
</div>