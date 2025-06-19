@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-6">新規投稿作成</h2>

  <div x-data="postForm()" x-init="init()" class="max-w-3xl mx-auto">
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
      @csrf

      <!-- 店舗選択（必須） -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">1. 店舗を選択 <span class="text-red-500">*</span></h3>

        <!-- 既存店舗から選択 -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">店舗を選択してください</label>
          <select name="post[shop_id]" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            <option value="">-- 選択してください --</option>
            @php
            // 仮のデータ（実際はコントローラーから渡す）
            $shops = \App\Models\Shop::orderBy('name')->get();
            @endphp
            @foreach($shops as $shop)
            <option value="{{ $shop->id }}">{{ $shop->name }} ({{ $shop->address }})</option>
            @endforeach
          </select>
        </div>

        <p class="text-sm text-gray-600">
          お探しの店舗が見つからない場合は、
          <a href="#" class="text-blue-600 hover:underline">新しい店舗を追加</a>
        </p>
      </div>

      <!-- 写真（オプション） -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-2">2. 写真を追加（任意）</h3>
        <p class="text-sm text-gray-600 mb-4">お店の雰囲気や料理の写真を追加できます</p>

        <!-- ドラッグ&ドロップエリア -->
        <div
          @dragover.prevent="dragOver = true"
          @dragleave.prevent="dragOver = false"
          @drop.prevent="handleDrop($event)"
          :class="{'border-blue-500 bg-blue-50': dragOver}"
          class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center transition-colors">

          <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
          </svg>

          <label class="inline-block">
            <input
              type="file"
              name="images[]"
              multiple
              accept="image/*"
              @change="handleFileSelect($event)"
              class="hidden">
            <span class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 cursor-pointer">
              ファイルを選択
            </span>
          </label>
        </div>

        <!-- プレビュー -->
        <div x-show="images.length > 0" class="mt-6 grid grid-cols-3 gap-4">
          <template x-for="(image, index) in images" :key="index">
            <div class="relative group">
              <img :src="image.preview" class="w-full h-32 object-cover rounded-lg">
              <button
                type="button"
                @click="removeImage(index)"
                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100">
                ×
              </button>
            </div>
          </template>
        </div>
      </div>

      <!-- 詳細情報 -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">3. 詳細情報</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- 訪問日時 -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">訪問日時</label>
            <input
              type="datetime-local"
              name="post[visit_time]"
              value="{{ now()->format('Y-m-d\TH:i') }}"
              class="w-full px-3 py-2 border border-gray-300 rounded-md">
          </div>

          <!-- 予算 -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">予算（円）</label>
            <select name="post[budget]" class="w-full px-3 py-2 border border-gray-300 rounded-md">
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
            <label class="block text-sm font-medium text-gray-700 mb-1">リピートしたいメニュー</label>
            <input
              type="text"
              name="post[repeat_menu]"
              placeholder="例：特製ラーメン"
              class="w-full px-3 py-2 border border-gray-300 rounded-md">
          </div>

          <!-- 気になるメニュー -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">気になるメニュー</label>
            <input
              type="text"
              name="post[interest_menu]"
              placeholder="例：餃子セット"
              class="w-full px-3 py-2 border border-gray-300 rounded-md">
          </div>
        </div>

        <!-- メモ -->
        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">メモ・感想</label>
          <textarea
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

      <!-- ボタン -->
      <div class="flex justify-between">
        <a href="{{ route('posts.index') }}" class="px-6 py-3 text-gray-600 hover:text-gray-800">
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

<script>
  function postForm() {
    return {
      images: [],
      dragOver: false,

      init() {
        console.log('Form initialized');
      },

      handleFileSelect(event) {
        const files = Array.from(event.target.files);
        this.processFiles(files);
      },

      handleDrop(event) {
        this.dragOver = false;
        const files = Array.from(event.dataTransfer.files);
        this.processFiles(files);
      },

      processFiles(files) {
        files.forEach(file => {
          if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
              this.images.push({
                file: file,
                preview: e.target.result
              });
            };
            reader.readAsDataURL(file);
          }
        });
      },

      removeImage(index) {
        this.images.splice(index, 1);
      }
    };
  }
</script>
@endsection