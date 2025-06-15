<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
      <!-- ページヘッダー -->
      <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-neutral-900 mb-4">店舗・グルメ検索</h1>
        <p class="text-neutral-600">お気に入りの店舗や料理を見つけよう</p>
      </div>

      <!-- 検索フォーム -->
      <div class="bg-white rounded-xl shadow-card p-6 mb-8">
        <form method="GET" action="{{ route('search') }}" class="space-y-4">
          <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
              <x-atoms.input
                type="text"
                name="q"
                placeholder="店舗名、料理名、エリアで検索..."
                value="{{ request('q') }}"
                class="w-full" />
            </div>
            <div>
              <x-atoms.button variant="primary" type="submit" class="w-full md:w-auto">
                検索
              </x-atoms.button>
            </div>
          </div>
        </form>
      </div>

      <!-- 検索結果 -->
      @if(request('q'))
      <div class="bg-white rounded-xl shadow-card p-6">
        <h2 class="text-xl font-semibold text-neutral-900 mb-4">
          "{{ request('q') }}" の検索結果
        </h2>

        <div class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <h3 class="text-lg font-medium text-neutral-900 mb-2">検索機能は開発中です</h3>
          <p class="text-neutral-600">現在、検索機能を実装中です。しばらくお待ちください。</p>
        </div>
      </div>
      @else
      <!-- 初期状態 -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- 人気のカテゴリ -->
        <div class="bg-white rounded-xl shadow-card p-6">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4">人気のカテゴリ</h3>
          <div class="space-y-2">
            <x-atoms.badge variant="primary" class="mr-2">ラーメン</x-atoms.badge>
            <x-atoms.badge variant="primary" class="mr-2">寿司</x-atoms.badge>
            <x-atoms.badge variant="primary" class="mr-2">イタリアン</x-atoms.badge>
            <x-atoms.badge variant="primary" class="mr-2">カフェ</x-atoms.badge>
          </div>
        </div>

        <!-- 人気のエリア -->
        <div class="bg-white rounded-xl shadow-card p-6">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4">人気のエリア</h3>
          <div class="space-y-2">
            <x-atoms.badge variant="secondary" class="mr-2">渋谷</x-atoms.badge>
            <x-atoms.badge variant="secondary" class="mr-2">新宿</x-atoms.badge>
            <x-atoms.badge variant="secondary" class="mr-2">銀座</x-atoms.badge>
            <x-atoms.badge variant="secondary" class="mr-2">表参道</x-atoms.badge>
          </div>
        </div>

        <!-- 最近の検索 -->
        <div class="bg-white rounded-xl shadow-card p-6">
          <h3 class="text-lg font-semibold text-neutral-900 mb-4">最近の検索</h3>
          <p class="text-neutral-600 text-sm">検索履歴はありません</p>
        </div>
      </div>
      @endif
    </div>
  </div>
</x-app-layout>