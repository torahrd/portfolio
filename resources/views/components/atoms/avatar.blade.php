@props([
'user' => null,
'size' => 'default', // 'small', 'default', 'large'
'online' => false,
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

$initialsColors = [
'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-yellow-500',
'bg-lime-500', 'bg-green-500', 'bg-emerald-500', 'bg-teal-500',
'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-indigo-500',
'bg-violet-500', 'bg-purple-500', 'bg-fuchsia-500', 'bg-pink-500', 'bg-rose-500'
];
$initialsColor = $user ? $initialsColors[$user->id % count($initialsColors)] : 'bg-neutral-200';

$avatarClasses = [
'relative inline-block rounded-full overflow-hidden',
$avatarSize
];
@endphp

@if($user)
<div {{ $attributes->merge(['class' => implode(' ', $avatarClasses)]) }}>
  <a href="{{ route('profile.show', $user) }}" class="block w-full h-full">
    @if ($user->avatar)
    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
    @else
    <div class="w-full h-full flex items-center justify-center {{ $initialsColor }}">
      <span class="font-bold text-white {{ $fontSize }}">
        {{ \App\Helpers\getInitial($user->name) }}
      </span>
    </div>
    @endif
  </a>

  @if($online)
  <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white"></span>
  @endif
</div>
@endif