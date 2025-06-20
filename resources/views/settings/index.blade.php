<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <!-- ページヘッダー -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">設定</h1>
        <p class="text-neutral-600">アカウントとアプリケーションの設定を管理します</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- サイドナビゲーション -->
        <div class="lg:col-span-1">
          <nav class="bg-white rounded-xl shadow-card p-4">
            <ul class="space-y-2">
              <li>
                <a href="#profile" class="flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg">
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  プロフィール
                </a>
              </li>
              <li>
                <a href="#privacy" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg">
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                  プライバシー
                </a>
              </li>
              <li>
                <a href="#notifications" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg">
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 17h5l-5 5v-5z"></path>
                  </svg>
                  通知設定
                </a>
              </li>
              <li>
                <a href="#account" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg">
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  アカウント
                </a>
              </li>
            </ul>
          </nav>
        </div>

        <!-- メインコンテンツ -->
        <div class="lg:col-span-3">
          <!-- プロフィール設定 -->
          <div id="profile" class="bg-white rounded-xl shadow-card p-6 mb-8">
            <h2 class="text-xl font-semibold text-neutral-900 mb-4">プロフィール設定</h2>

            <div class="flex items-center space-x-6 mb-6">
              <x-atoms.avatar
                :src="auth()->user()->avatar_url"
                :alt="auth()->user()->name"
                size="xl" />
              <div>
                <h3 class="text-lg font-medium text-neutral-900">{{ auth()->user()->name }}</h3>
                <p class="text-neutral-600">{{ auth()->user()->email }}</p>
                <x-atoms.button variant="secondary" size="sm" class="mt-2">
                  写真を変更
                </x-atoms.button>
              </div>
            </div>

            <div class="flex justify-end">
              <x-atoms.button variant="primary" href="{{ route('profile.edit') }}">
                プロフィールを編集
              </x-atoms.button>
            </div>
          </div>

          <!-- プライバシー設定 -->
          <div id="privacy" class="bg-white rounded-xl shadow-card p-6 mb-8">
            <h2 class="text-xl font-semibold text-neutral-900 mb-4">プライバシー設定</h2>

            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-neutral-900">プロフィールを非公開にする</h3>
                  <p class="text-sm text-neutral-600">フォロワーのみが投稿を見ることができます</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer">
                  <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-neutral-900">フォロー申請の承認が必要</h3>
                  <p class="text-sm text-neutral-600">新しいフォロワーを手動で承認します</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer">
                  <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>
            </div>
          </div>

          <!-- 通知設定 -->
          <div id="notifications" class="bg-white rounded-xl shadow-card p-6 mb-8">
            <h2 class="text-xl font-semibold text-neutral-900 mb-4">通知設定</h2>

            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-neutral-900">いいね通知</h3>
                  <p class="text-sm text-neutral-600">投稿にいいねされた時に通知します</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-neutral-900">コメント通知</h3>
                  <p class="text-sm text-neutral-600">投稿にコメントされた時に通知します</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-sm font-medium text-neutral-900">フォロー通知</h3>
                  <p class="text-sm text-neutral-600">新しいフォロワーがいる時に通知します</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked>
                  <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>
            </div>
          </div>

          <!-- アカウント設定 -->
          <div id="account" class="bg-white rounded-xl shadow-card p-6">
            <h2 class="text-xl font-semibold text-neutral-900 mb-4">アカウント設定</h2>

            <div class="space-y-4">
              <x-atoms.button-secondary class="w-full justify-start">
                パスワードを変更
              </x-atoms.button-secondary>

              <x-atoms.button-secondary class="w-full justify-start">
                データをエクスポート
              </x-atoms.button-secondary>

              <hr class="my-6">

              <div class="bg-error-50 border border-error-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-error-800 mb-2">危険な操作</h3>
                <p class="text-sm text-error-600 mb-4">以下の操作は元に戻すことができません。</p>

                <x-atoms.button-secondary class="text-error-600 hover:text-error-700 hover:bg-error-100">
                  アカウントを削除
                </x-atoms.button-secondary>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>