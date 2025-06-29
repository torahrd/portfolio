@props([
'notification',
'showActions' => true
])

@php
$typeClasses = [
'like' => 'text-pink-500',
'comment' => 'text-blue-500',
'follow' => 'text-primary-500',
'mention' => 'text-purple-500',
'system' => 'text-neutral-500'
];

$typeClass = $typeClasses[$notification->type] ?? $typeClasses['system'];
@endphp

<div class="bg-white rounded-xl shadow-card p-4 hover:shadow-card-hover transition-shadow duration-300 {{ $notification->read_at ? '' : 'border-l-4 border-primary-500 bg-primary-50' }}">
  <div class="flex items-start space-x-3">
    <!-- アイコン -->
    <div class="flex-shrink-0">
      @if($notification->type === 'like')
      <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center">
        <svg class="w-4 h-4 {{ $typeClass }}" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
        </svg>
      </div>
      @elseif($notification->type === 'comment')
      <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
        <svg class="w-4 h-4 {{ $typeClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
      </div>
      @elseif($notification->type === 'follow')
      <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
        <svg class="w-4 h-4 {{ $typeClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
      </div>
      @else
      <div class="w-8 h-8 bg-neutral-100 rounded-full flex items-center justify-center">
        <svg class="w-4 h-4 {{ $typeClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 17h5l-5 5v-5z"></path>
        </svg>
      </div>
      @endif
    </div>

    <!-- 通知内容 -->
    <div class="flex-1 min-w-0">
      <div class="flex items-start justify-between">
        <div class="flex-1">
          @if($notification->type === 'App\\Notifications\\FollowRequestNotification')
          <div class="flex items-center space-x-2 mb-2">
            <a href="{{ $notification->data['profile_url'] ?? '#' }}">
              <img src="{{ $notification->data['from_user_avatar'] ?? asset('images/default-avatar.png') }}" alt="avatar" class="w-8 h-8 rounded-full object-cover">
            </a>
            <a href="{{ $notification->data['profile_url'] ?? '#' }}" class="font-semibold text-primary-700 hover:underline">
              {{ $notification->data['from_user_name'] ?? 'ユーザー' }}
            </a>
          </div>
          @endif
          <p class="text-sm text-neutral-900">
            {!! $notification->message !!}
          </p>
          <p class="text-xs text-neutral-500 mt-1">
            {{ $notification->created_at->diffForHumans() }}
          </p>
        </div>

        @if($showActions)
        <div class="flex items-center space-x-1 ml-4">
          @if($notification->type === 'App\\Notifications\\FollowRequestNotification')
          <form method="POST" action="{{ route('notifications.accept', $notification->id) }}" class="inline">
            @csrf
            <x-atoms.button-primary type="submit">
              承認
            </x-atoms.button-primary>
          </form>
          <form method="POST" action="{{ route('notifications.reject', $notification->id) }}" class="inline ml-2">
            @csrf
            <x-atoms.button-secondary type="submit">
              拒否
            </x-atoms.button-secondary>
          </form>
          @else
          @if(!$notification->read_at)
          <x-atoms.button-icon
            wire:click="markAsRead('{{ $notification->id }}')"
            title="既読にする">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </x-atoms.button-icon>
          @endif

          <x-atoms.button-icon
            wire:click="deleteNotification('{{ $notification->id }}')"
            title="削除">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
          </x-atoms.button-icon>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>
</div>