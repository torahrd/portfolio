<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
      <div class="bg-white rounded-lg shadow-md p-8">
        <div class="mb-6">
          <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
          <p class="text-gray-600">このアカウントはプライベートです</p>

          @auth
          @if(auth()->id() !== $user->id)
          <div class="mt-6">
            @if(auth()->user()->isFollowing($user))
            <form method="POST" action="{{ route('users.unfollow', $user) }}">
              @csrf
              <button type="submit" class="w-full btn btn-outline-secondary">フォロー解除</button>
            </form>
            @elseif(auth()->user()->hasSentFollowRequest($user))
            <button class="w-full btn btn-disabled" disabled>申請中</button>
            @else
            <form method="POST" action="{{ route('users.follow', $user) }}">
              @csrf
              <button type="submit" class="w-full btn btn-primary">フォロー申請</button>
            </form>
            @endif
          </div>
          @endif
          @endauth
        </div>

        <div class="border-t pt-6">
          <div class="flex justify-center space-x-8 mb-6">
            <div class="text-center">
              <div class="text-lg font-semibold text-gray-800">{{ $user->posts_count }}</div>
              <div class="text-sm text-gray-500">投稿</div>
            </div>
            <div class="text-center">
              <div class="text-lg font-semibold text-gray-800">{{ $user->followers_count }}</div>
              <div class="text-sm text-gray-500">フォロワー</div>
            </div>
            <div class="text-center">
              <div class="text-lg font-semibold text-gray-800">{{ $user->following_count }}</div>
              <div class="text-sm text-gray-500">フォロー中</div>
            </div>
          </div>

          <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-center justify-center mb-4">
              <svg class="w-8 h-8 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
              <h2 class="text-lg font-semibold text-gray-900">プライベートアカウント</h2>
            </div>
            <p class="text-gray-600 mb-4">
              このユーザーの投稿や詳細情報を表示するには、フォローする必要があります。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

@push('scripts')
<script>
  function toggleFollow(userId) {
    const button = document.getElementById(`follow-button-${userId}`);
    const url = button.dataset.followUrl;

    // ボタンを無効化
    button.disabled = true;
    button.textContent = '処理中...';

    fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // フォロー状態に応じてボタンの表示を更新
          if (data.following) {
            button.textContent = 'フォロー解除';
            button.classList.remove('btn-primary');
            button.classList.add('btn-outline-secondary');
          } else {
            button.textContent = 'フォローする';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-primary');
          }

          // 成功メッセージを表示
          if (data.message) {
            // トースト通知やアラートでメッセージを表示
            alert(data.message);
          }

          // フォロー成功時はページをリロードしてプロフィールを表示
          if (data.following) {
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          }
        } else {
          // エラーメッセージを表示
          alert(data.message || 'エラーが発生しました');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('エラーが発生しました');
      })
      .finally(() => {
        // ボタンを再有効化
        button.disabled = false;
      });
  }
</script>
@endpush