<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <!-- ページヘッダー -->
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">通知</h1>

        @if(auth()->user()->unreadNotifications->count() > 0)
        <x-atoms.button-secondary size="sm">
          すべて既読にする
        </x-atoms.button-secondary>
        @endif
      </div>

      <!-- 通知一覧 -->
      <div class="space-y-4">
        @forelse(auth()->user()->notifications as $notification)
        <x-molecules.notification-card
          :notification="$notification"
          :show-actions="true" />
        @empty
        <!-- 通知なしの状態 -->
        <div class="bg-white rounded-xl shadow-card p-12 text-center">
          <svg class="mx-auto h-16 w-16 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 17h5l-5 5v-5z"></path>
          </svg>
          <h3 class="text-xl font-semibold text-neutral-900 mb-2">通知はありません</h3>
          <p class="text-neutral-600 mb-6">新しい通知があるとここに表示されます</p>

          <x-atoms.button-primary href="{{ route('posts.index') }}">
            投稿を見る
          </x-atoms.button-primary>
        </div>
        @endforelse
      </div>

      <!-- ページネーション（将来の拡張用） -->
      @if(auth()->user()->notifications->count() > 20)
      <div class="mt-8 flex justify-center">
        <x-atoms.button-secondary>
          さらに読み込む
        </x-atoms.button-secondary>
      </div>
      @endif
    </div>
  </div>
</x-app-layout>