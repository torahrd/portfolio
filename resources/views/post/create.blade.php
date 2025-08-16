@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">新規投稿作成</h2>

    <div class="max-w-3xl mx-auto">
      @if ($errors->any())
      <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- 店舗選択（Bladeコンポーネント化） -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h3 class="text-lg font-bold mb-4">1. 店舗を選択 <span class="text-red-500">*</span></h3>
          <x-shop-search name="post[shop_id]" label="店舗名を検索してください" />
        </div>

        <!-- 写真（オプション） -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h3 class="text-lg font-bold mb-2">2. 写真を追加（任意）</h3>
          <p class="text-sm text-gray-600 mb-4">お店の雰囲気や料理の写真を追加できます</p>

          <!-- Cloudinary用：単一画像アップロードinput -->
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
                value="{{ now()->format('Y-m-d\\TH:i') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>

            <!-- 予算 -->
            <div>
              <label for="budget" class="block text-sm font-medium text-gray-700 mb-1">予算（円）</label>
              <select id="budget" name="post[budget]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">選択してください</option>
                <option value="1000">〜1,000円</option>
                <option value="2000">1,000〜2,000円</option>
                <option value="3000">2,000〜3,000円</option>
                <option value="5000">3,000〜5,000円</option>
                <option value="10000">5,000〜10,000円</option>
                <option value="30000">10,000〜30,000円</option>
                <option value="50000">30,000円〜</option>
              </select>
            </div>

            <!-- リピートしたいメニュー -->
            <div>
              <label for="repeat_menu" class="block text-sm font-medium text-gray-700 mb-1">リピートしたいメニュー</label>
              <input
                id="repeat_menu"
                type="text"
                name="post[repeat_menu]"
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
              class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
          </div>

          <!-- 訪問ステータス -->
          <div class="mt-4 space-y-2">
            <label class="inline-flex items-center">
              <input type="hidden" name="post[visit_status]" value="0">
              <input type="checkbox" name="post[visit_status]" value="1" class="mr-2" checked>
              <span class="text-sm">訪問済み</span>
            </label>

            <label class="inline-flex items-center ml-6">
              <input type="hidden" name="post[private_status]" value="0">
              <input type="checkbox" name="post[private_status]" value="1" class="mr-2">
              <span class="text-sm">非公開にする</span>
            </label>
          </div>
        </div>

        <!-- ボタン（左：キャンセル、右：投稿） -->
        <div class="flex justify-between">
          <a href="{{ url()->previous() }}" class="px-6 py-3 text-gray-600 hover:text-gray-800">
            キャンセル
          </a>
          <button
            type="submit"
            class="px-6 py-3 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
            投稿する
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection