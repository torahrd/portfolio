<div class="bg-white rounded-xl shadow p-6 h-full flex flex-col items-center text-center">
  <x-atoms.avatar :user="$user" size="large" />
  <h1 class="text-2xl font-bold text-gray-900 mt-4 mb-2">{{ $user->name }}</h1>
  <div class="flex justify-center space-x-6 my-4">
    <div class="text-center">
      <div class="text-lg font-semibold text-gray-800">{{ $user->posts_count }}</div>
      <div class="text-xs text-gray-500">投稿</div>
    </div>
    <div class="text-center">
      <a href="{{ route('profile.followers', $user) }}" class="text-lg font-semibold text-blue-600 hover:underline">{{ $user->followers()->count() }}</a>
      <div class="text-xs text-gray-500">フォロワー</div>
    </div>
    <div class="text-center">
      <a href="{{ route('profile.following', $user) }}" class="text-lg font-semibold text-blue-600 hover:underline">{{ $user->following()->count() }}</a>
      <div class="text-xs text-gray-500">フォロー中</div>
    </div>
  </div>
  @if($user->bio)
  <div class="w-full mt-4">
    <p class="text-gray-600 text-sm break-words leading-relaxed">
      {{ Str::limit($user->bio, 200) }}
    </p>
  </div>
  @endif
  <div class="w-full mt-6 space-y-3">
    @if($user->location)
    <div class="flex items-center text-gray-500 text-sm">
      <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      <span class="truncate">{{ $user->location }}</span>
    </div>
    @endif
    @if($user->website)
    <div class="flex items-center text-gray-500 text-sm">
      <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      <a href="{{ $user->website }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline truncate">{{ $user->website }}</a>
    </div>
    @endif
    <div class="flex items-center text-gray-500 text-sm">
      <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
      </svg>
      <span>{{ $user->created_at->format('Y年n月') }}に参加</span>
    </div>
  </div>
  @auth
  @if(auth()->id() === $user->id)
  <div class="w-full mt-6">
    <a href="{{ route('profile.edit') }}" class="w-full btn btn-outline-secondary">プロフィールを編集</a>
  </div>
  @else
  <div class="w-full mt-6">
    <button
      id="follow-button-{{ $user->id }}"
      data-user-id="{{ $user->id }}"
      data-follow-url="{{ route('users.follow', $user) }}"
      class="w-full btn {{ auth()->user()->isFollowing($user) ? 'btn-outline-secondary' : 'btn-primary' }} follow-button"
      onclick="toggleFollow('{{ $user->id }}')">
      @if(auth()->user()->isFollowing($user))
      フォロー解除
      @elseif(auth()->user()->hasSentFollowRequest($user))
      申請中
      @else
      フォロー
      @endif
    </button>
  </div>
  @endif
  @endauth
</div>

<script>
  function toggleFollow(userId) {
    const button = document.getElementById(`follow-button-${userId}`);
    const followUrl = button.dataset.followUrl;

    // ボタンを無効化
    button.disabled = true;
    button.textContent = '処理中...';

    fetch(followUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // ボタンの表示を更新
          if (data.is_following) {
            button.textContent = 'フォロー解除';
            button.className = 'w-full btn btn-outline-secondary follow-button';
          } else {
            button.textContent = data.is_pending ? '申請中' : 'フォロー';
            button.className = 'w-full btn btn-primary follow-button';
          }

          // フォロワー数を更新
          const followersElement = document.querySelector(`a[href*="/users/${userId}/followers"]`);
          if (followersElement) {
            followersElement.textContent = data.followers_count;
          }

          // 成功メッセージを表示（オプション）
          console.log(data.message);
        } else {
          alert('フォロー処理に失敗しました。');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('フォロー処理に失敗しました。');
      })
      .finally(() => {
        // ボタンを再有効化
        button.disabled = false;
      });
  }
</script>