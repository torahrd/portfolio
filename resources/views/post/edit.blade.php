<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">投稿を編集</h2>

    <div class="max-w-3xl mx-auto">
      <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 店舗選択（Bladeコンポーネント化） -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h3 class="text-lg font-bold mb-4">1. 店舗を選択 <span class="text-red-500">*</span></h3>
          <x-shop-search name="post[shop_id]" :initial-shop="$post->shop" label="店舗名を検索してください" />
        </div>

        <!-- 写真（Cloudinary 1枚、既存画像プレビュー＋差し替え可） -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h3 class="text-lg font-bold mb-2">2. 写真を追加（任意）</h3>
          <p class="text-sm text-gray-600 mb-4">お店の雰囲気や料理の写真を追加できます</p>
          @if($post->image_url)
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">現在の画像</label>
            <img src="{{ $post->image_url }}" alt="投稿画像" class="w-full max-w-xs rounded-lg border mb-2">
          </div>
          @endif
          <div class="mt-4">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">投稿画像（Cloudinary連携）</label>
            <input
              id="image"
              type="file"
              name="image"
              accept="image/*"
              class="w-full px-3 py-2 border border-gray-300 rounded-md">
            <p class="text-xs text-gray-500 mt-1">※ 1枚のみアップロード可能です。Cloudinaryに保存されます。</p>
          </div>
        </div>

        <!-- 詳細情報 -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h3 class="text-lg font-bold mb-4">3. 詳細情報</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 訪問日時 -->
            <div>
              <label for="visit_time" class="block text-sm font-medium text-gray-700 mb-1">訪問日時</label>
              <input
                id="visit_time"
                type="datetime-local"
                name="post[visit_time]"
                value="{{ $post->visit_time ? date('Y-m-d\TH:i', strtotime($post->visit_time)) : '' }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <!-- 予算 -->
            <div>
              <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">予算（円）</label>
              <select id="budget" name="post[budget]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">選択してください</option>
                <option value="1000" {{ $post->budget == 1000 ? 'selected' : '' }}>〜1,000円</option>
                <option value="2000" {{ $post->budget == 2000 ? 'selected' : '' }}>1,000〜2,000円</option>
                <option value="3000" {{ $post->budget == 3000 ? 'selected' : '' }}>2,000〜3,000円</option>
                <option value="5000" {{ $post->budget == 5000 ? 'selected' : '' }}>3,000〜5,000円</option>
                <option value="10000" {{ $post->budget == 10000 ? 'selected' : '' }}>5,000〜10,000円</option>
                <option value="30000" {{ $post->budget == 30000 ? 'selected' : '' }}>10,000〜30,000円</option>
                <option value="50000" {{ $post->budget == 50000 ? 'selected' : '' }}>30,000円〜</option>
              </select>
            </div>
            <!-- リピートしたいメニュー -->
            <div>
              <label for="repeat_menu" class="block text-sm font-medium text-gray-700 mb-1">リピートしたいメニュー</label>
              <input
                id="repeat_menu"
                type="text"
                name="post[repeat_menu]"
                value="{{ $post->repeat_menu }}"
                placeholder="例：特製ラーメン"
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <!-- 気になるメニュー -->
            <div>
              <label for="interest_menu" class="block text-sm font-medium text-gray-700 mb-1">気になるメニュー</label>
              <input
                id="interest_menu"
                type="text"
                name="post[interest_menu]"
                value="{{ $post->interest_menu }}"
                placeholder="例：餃子セット"
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
          </div>
          <!-- メモ -->
          <div class="mt-4">
            <label for="memo" class="block text-sm font-medium text-gray-700 mb-1">メモ・感想</label>
            <textarea
              id="memo"
              name="post[memo]"
              rows="4"
              placeholder="お店の雰囲気、料理の感想、サービスについてなど..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ $post->memo }}</textarea>
          </div>
          <!-- 訪問ステータス -->
          <div class="mt-4 space-y-2">
            <label class="inline-flex items-center">
              <input type="hidden" name="post[visit_status]" value="0">
              <input type="checkbox" name="post[visit_status]" value="1" class="mr-2" {{ $post->visit_status ? 'checked' : '' }}>
              <span class="text-sm">訪問済み</span>
            </label>
            <label class="inline-flex items-center ml-6">
              <input type="hidden" name="post[private_status]" value="0">
              <input type="checkbox" name="post[private_status]" value="1" class="mr-2" {{ $post->private_status ? 'checked' : '' }}>
              <span class="text-sm">非公開にする</span>
            </label>
          </div>
        </div>

        <!-- ボタン -->
        <div class="flex justify-between">
          <a href="{{ route('posts.show', $post->id) }}" class="px-6 py-3 text-gray-600 hover:text-gray-800">
            キャンセル
          </a>
          <button
            type="submit"
            class="px-6 py-3 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
            更新
          </button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>