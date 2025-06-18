@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-6">お気に入りのお店の思い出を共有しましょう</h2>

  <!-- ステップインジケーター -->
  <div class="flex justify-between items-center mb-8 max-w-3xl mx-auto">
    <div class="flex items-center">
      <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center font-bold">1</div>
      <div class="ml-2 text-sm">写真</div>
    </div>
    <div class="w-24 h-1 bg-gray-300"></div>
    <div class="flex items-center">
      <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
      <div class="ml-2 text-sm text-gray-600">店舗選択</div>
    </div>
    <div class="w-24 h-1 bg-gray-300"></div>
    <div class="flex items-center">
      <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">3</div>
      <div class="ml-2 text-sm text-gray-600">詳細情報</div>
    </div>
    <div class="w-24 h-1 bg-gray-300"></div>
    <div class="flex items-center">
      <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">4</div>
      <div class="ml-2 text-sm text-gray-600">確認</div>
    </div>
  </div>

  <!-- フォーム -->
  <div x-data="postForm()" x-init="init()" class="max-w-3xl mx-auto">
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
      @csrf

      <!-- 写真追加セクション -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">写真を追加</h3>

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

          <p class="text-gray-600 mb-2">画像をドラッグ&ドロップ</p>
          <p class="text-gray-500 text-sm mb-4">または</p>

          <label class="inline-block">
            <input
              type="file"
              multiple
              accept="image/*"
              @change="handleFileSelect($event)"
              class="hidden">
            <span class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 cursor-pointer">
              ファイルを選択
            </span>
          </label>
        </div>

        <!-- プレビューエリア -->
        <div x-show="images.length > 0" class="mt-6">
          <div class="grid grid-cols-3 gap-4">
            <template x-for="(image, index) in images" :key="index">
              <div class="relative group">
                <img :src="image.preview" class="w-full h-32 object-cover rounded-lg">
                <button
                  type="button"
                  @click="removeImage(index)"
                  class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- 次へボタン -->
      <div class="flex justify-end">
        <button
          type="button"
          @click="nextStep"
          class="px-6 py-3 bg-red-500 text-white rounded-md hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="images.length === 0">
          次へ
        </button>
      </div>
    </form>
  </div>
</div>

@verbatim
<script>
  function postForm() {
    return {
      images: [],
      dragOver: false,
      recentShops: [],

      init() {
        // PHPから最近の店舗データを受け取る
        const recentShopsEl = document.getElementById('recentShopsData');
        if (recentShopsEl && recentShopsEl.value) {
          try {
            this.recentShops = JSON.parse(recentShopsEl.value);
          } catch (e) {
            console.error('最近の店舗データの解析に失敗しました:', e);
          }
        }
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
      },

      nextStep() {
        if (this.images.length === 0) {
          alert('少なくとも1枚の写真を追加してください。');
          return;
        }

        // ここで次のステップに進む処理
        // 例: フォームデータを保存してから次のページへ
        console.log('次のステップへ進む', this.images);

        // 実際の実装では、画像をFormDataに追加して送信するか、
        // セッションに保存してから次のページへリダイレクト
      }
    };
  }
</script>
@endverbatim

<!-- PHPデータを受け渡すための隠し要素 -->
@if(isset($recentShops) && $recentShops->isNotEmpty())
<input type="hidden" id="recentShopsData" value='@json($recentShops)'>
@endif
@endsection