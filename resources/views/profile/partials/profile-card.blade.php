<div class="bg-white rounded-xl shadow p-6 h-full flex flex-col items-center text-center" style="min-height: calc(100vh - 8rem);">
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
  <!-- スペーサー -->
  <div class="flex-grow"></div>

  @auth
  @if(auth()->id() === $user->id)
  <div class="w-full mt-6 space-y-3">
    {{-- プロフィールリンク機能（全ユーザー対象） --}}
    @php
    $activeProfileLink = $user->profileLinks()->where('is_active', true)->where('expires_at', '>', now())->first();
    $contextSuffix = $context ?? 'default';
    $modalName = 'profile-link-modal-' . $user->id . '-' . $contextSuffix;
    @endphp

    @if($activeProfileLink)
    {{-- 有効なプロフィールリンクがある場合 --}}
    <button
      onclick="toggleProfileLinkDisplay('{{ $contextSuffix }}')"
      class="w-full px-4 py-2 border border-blue-500 text-blue-500 rounded-md hover:border-blue-600 hover:text-blue-600 transition-colors duration-200">
      プロフィールリンク表示
    </button>

    {{-- インライン展開エリア --}}
    <div id="profile-link-content-{{ $contextSuffix }}" class="hidden w-full mt-3 p-4 bg-white border border-blue-200 rounded-lg shadow-sm space-y-3">
      {{-- リンクURL表示 --}}
      <div>
        <label for="profile-link-url-{{ $contextSuffix }}" class="block text-sm font-medium text-gray-700 mb-2">リンクURL</label>
        <div class="flex">
          <input
            type="text"
            id="profile-link-url-{{ $contextSuffix }}"
            class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-white text-sm"
            value="{{ route('profile.show-by-token', $activeProfileLink->token) }}"
            readonly>
          <button
            onclick="copyProfileLink('profile-link-url-{{ $contextSuffix }}', 'copy-status-{{ $contextSuffix }}', 'manual-copy-guide-{{ $contextSuffix }}')"
            class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
          </button>
        </div>
        <p class="text-xs text-gray-500 mt-1">このリンクを共有することで、プロフィールを直接表示できます（3日間有効）</p>
      </div>

      {{-- 有効期限表示 --}}
      <div>
        <div class="block text-sm font-medium text-gray-700 mb-2">有効期限</div>
        <p class="text-sm text-gray-600">
          {{ $activeProfileLink->expires_at->format('Y年n月j日 H:i') }}まで有効
        </p>
      </div>

      {{-- コピー状況表示 --}}
      <div id="copy-status-{{ $contextSuffix }}" class="hidden">
        <div class="flex items-center text-green-600 text-sm">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          リンクをコピーしました！
        </div>
      </div>

      {{-- 手動コピー案内 --}}
      <div id="manual-copy-guide-{{ $contextSuffix }}" class="hidden">
        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
          <div class="flex items-center text-yellow-800 text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            自動コピーに失敗しました。上記のリンクを手動でコピーしてください。
          </div>
        </div>
      </div>

      {{-- アクションボタン --}}
      <div class="flex justify-between items-center pt-3 border-t border-gray-200">
        <button
          onclick="deactivateProfileLink()"
          class="px-4 py-2 text-red-600 text-sm font-medium hover:bg-red-50 rounded-md">
          リンクを無効化
        </button>
        <button
          onclick="closeProfileLinkDisplay('{{ $contextSuffix }}')"
          class="px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
          閉じる
        </button>
      </div>
    </div>
    @else
    {{-- 有効なプロフィールリンクがない場合 --}}
    <button
      onclick="generateProfileLink()"
      class="w-full px-4 py-2 border border-blue-500 text-blue-500 rounded-md hover:border-blue-600 hover:text-blue-600 transition-colors duration-200">
      プロフィールリンク作成
    </button>
    @endif

    {{-- プロフィール編集ボタン --}}
    <a href="{{ route('profile.edit') }}" class="w-full px-4 py-2 border border-gray-500 text-gray-500 rounded-md hover:border-gray-600 hover:text-gray-600 transition-colors duration-200 text-center block">プロフィールを編集</a>
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
  // ページ読み込み時の自動表示チェック
  document.addEventListener('DOMContentLoaded', function() {
    // リロード後にプロフィールリンクを自動表示する
    if (sessionStorage.getItem('autoShowProfileLink') === 'true') {
      sessionStorage.removeItem('autoShowProfileLink');
      // 少し遅延させてDOM要素が確実に読み込まれてから実行
      setTimeout(() => {
        toggleProfileLinkDisplay('desktop');
      }, 100);
    }
  });

  // プロフィールリンク生成
  async function generateProfileLink() {
    try {
      const response = await fetch('{{ route("profile.generate-link") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (data.success) {
        // リンク生成成功 - 動的にUI更新
        updateProfileLinkUI(data.link, data.expires_at);
      } else {
        alert(data.error || 'プロフィールリンクの生成に失敗しました');
      }
    } catch (error) {
      console.error('Error:', error);
      alert('プロフィールリンクの生成に失敗しました');
    }
  }

  // プロフィールリンクUI更新
  function updateProfileLinkUI(linkUrl, expiresAt) {
    // 作成ボタンを「プロフィールリンク表示」に変更
    const createButtons = document.querySelectorAll('button[onclick="generateProfileLink()"]');
    createButtons.forEach(button => {
      button.textContent = 'プロフィールリンク表示';
      button.setAttribute('onclick', 'toggleProfileLinkDisplay("desktop")');
    });

    // リンク表示エリアが存在しない場合は、リロード後に自動表示するためのフラグを設定
    const contentElement = document.getElementById('profile-link-content-desktop');
    if (!contentElement) {
      // リロード後に自動表示するためのフラグを設定
      sessionStorage.setItem('autoShowProfileLink', 'true');
      window.location.reload();
      return;
    }

    // リンク情報を更新
    const urlInput = document.getElementById('profile-link-url-desktop');
    if (urlInput) {
      urlInput.value = linkUrl;
    }

    // 有効期限を更新
    const expiresElement = contentElement.querySelector('.text-sm.text-gray-600');
    if (expiresElement) {
      expiresElement.textContent = `${expiresAt}まで有効`;
    }

    // リンク表示エリアを自動的に開く
    toggleProfileLinkDisplay('desktop');
  }

  // プロフィールリンクインライン表示（表示専用）
  function toggleProfileLinkDisplay(contextSuffix) {
    console.log('toggleProfileLinkDisplay called with contextSuffix:', contextSuffix); // デバッグ用

    const contentElement = document.getElementById(`profile-link-content-${contextSuffix}`);
    const showButton = document.querySelector(`button[onclick="toggleProfileLinkDisplay('${contextSuffix}')"]`);

    if (contentElement) {
      // 表示する
      contentElement.classList.remove('hidden');
      // 表示ボタンを非表示にする
      if (showButton) {
        showButton.style.display = 'none';
      }
    } else {
      console.error('Profile link content element not found');
    }
  }

  // プロフィールリンクインライン表示を閉じる（閉じる専用）
  function closeProfileLinkDisplay(contextSuffix) {
    console.log('closeProfileLinkDisplay called with contextSuffix:', contextSuffix); // デバッグ用

    const contentElement = document.getElementById(`profile-link-content-${contextSuffix}`);
    const showButton = document.querySelector(`button[onclick="toggleProfileLinkDisplay('${contextSuffix}')"]`);

    if (contentElement) {
      // 非表示にする
      contentElement.classList.add('hidden');
      // 表示ボタンを表示する
      if (showButton) {
        showButton.style.display = 'block';
      }
      // 閉じる時はコピー状況と手動案内を非表示にする
      const copyStatus = document.getElementById(`copy-status-${contextSuffix}`);
      const manualGuide = document.getElementById(`manual-copy-guide-${contextSuffix}`);
      if (copyStatus) copyStatus.classList.add('hidden');
      if (manualGuide) manualGuide.classList.add('hidden');
    } else {
      console.error('Profile link content element not found');
    }
  }

  // プロフィールリンク無効化
  async function deactivateProfileLink() {
    if (!confirm('プロフィールリンクを無効化しますか？\n無効化すると、このリンクは使用できなくなります。')) {
      return;
    }

    try {
      const response = await fetch('{{ route("profile.deactivate-link") }}', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (data.success) {
        // 無効化成功 - 動的にUI更新
        resetProfileLinkUI();
      } else {
        alert(data.message || 'プロフィールリンクの無効化に失敗しました');
      }
    } catch (error) {
      console.error('Error:', error);
      alert('プロフィールリンクの無効化に失敗しました');
    }
  }

  // プロフィールリンクUI初期化
  function resetProfileLinkUI() {
    // 無効化後は確実にリロードして状態を初期化
    window.location.reload();
  }

  // プロフィールリンクコピー
  async function copyProfileLink(urlInputId, copyStatusId, manualGuideId) {
    const linkUrl = document.getElementById(urlInputId).value;
    const copyStatus = document.getElementById(copyStatusId);
    const manualGuide = document.getElementById(manualGuideId);

    // 状態をリセット
    copyStatus.classList.add('hidden');
    manualGuide.classList.add('hidden');

    try {
      // Clipboard API を試行
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(linkUrl);
        copyStatus.classList.remove('hidden');
        setTimeout(() => copyStatus.classList.add('hidden'), 3000);
        return;
      }

      // execCommand を試行
      const textArea = document.createElement('textarea');
      textArea.value = linkUrl;
      textArea.style.position = 'fixed';
      textArea.style.left = '-999999px';
      textArea.style.top = '-999999px';
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();

      if (document.execCommand('copy')) {
        copyStatus.classList.remove('hidden');
        setTimeout(() => copyStatus.classList.add('hidden'), 3000);
      } else {
        throw new Error('execCommand failed');
      }

      document.body.removeChild(textArea);
    } catch (error) {
      console.error('Copy failed:', error);
      // 手動コピー案内を表示
      manualGuide.classList.remove('hidden');
    }
  }

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
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
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

          // ページ全体をリロードして全てのUI要素を即時反映
          window.location.reload();
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