@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">フォロー中一覧</h1>
      @if($followings->isEmpty())
      <p class="text-neutral-500">フォロー中のユーザーはいません。</p>
      @else
      <ul class="space-y-4">
        @foreach($followings as $user)
        <li class="flex items-center space-x-4 bg-white rounded-lg shadow p-4">
          <a href="{{ route('profile.show', $user->id) }}">
            <x-atoms.avatar :user="$user" size="large" />
          </a>
          <div>
            <a href="{{ route('profile.show', $user->id) }}" class="text-lg font-semibold text-neutral-900 hover:underline">{{ $user->name }}</a>
            @if(!empty($user->username))
            <div class="text-sm text-neutral-500">@ {{ $user->username }}</div>
            @endif
          </div>
        </li>
        @endforeach
      </ul>
      @endif
    </div>
  </div>
@endsection