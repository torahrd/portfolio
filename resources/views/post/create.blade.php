{{-- resources/views/posts/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-50">
  <div class="max-w-4xl mx-auto px-4 py-8">
    <!-- ヘッダー -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-neutral-900">新しい投稿を作成</h1>
      <p class="text-neutral-600 mt-2">お気に入りのお店の思い出を共有しましょう</p>
    </div>

    <!-- プログレスバー -->
    <div x-data="{ currentStep: 1, totalSteps: 4 }" class="mb-8">
      <div class="bg-white rounded-xl shadow-card p-6">
        <div class="flex items-center justify-between mb-4">
          <template x-for="step in totalSteps" :key="step">
            <div class="flex items-center flex-1">
              <div class="relative">
                <div :class="currentStep >= step ? 'bg-primary-500 border-primary-500' : 'bg-white border-neutral-300'"
                  class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition-colors duration-200">
                  <span :class="currentStep >= step ? 'text-white' : 'text-neutral-500'"
                    class="font-semibold" x-text="step"></span>
                </div>
                <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs text-neutral-600 whitespace-nowrap"
                  x-text="['写真', '店舗選択', '詳細情報', '確認'][step - 1]"></span>
              </div>
              <div x-show="step < totalSteps"
                :class="currentStep > step ? 'bg-primary-500' : 'bg-neutral-300'"
                class="flex-1 h-0.5 mx-4 transition-colors duration-200"></div>
            </div>
          </template>
        </div>
      </div>

      <!-- フォーム本体 -->
      <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
        class="mt-8" x-data="postForm()">
        @csrf

        <!-- Step 1: 写真アップロード -->
        <div x-show="currentStep === 1" x-transition class="bg-white rounded-xl shadow-card p-6">
          <h2 class="text-xl font-bold text-neutral-900 mb-6">写真を追加</h2>

          <div class="border-2 border-dashed border-neutral-300 rounded-xl p-8 text-center hover:border-primary-400 transition-colors duration-200"
            data-drop-zone
            @drop.prevent="handleDrop($event)"
            @dragover.prevent
            @dragenter.prevent
            @dragleave.prevent>

            <div x-show="images.length === 0">
              <svg class="mx-auto h-16 w-16 text-neutral-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="text-neutral-600 mb-2">クリックまたはドラッグ&ドロップで画像を追加</p>
              <p class="text-sm text-neutral-500">JPG, PNG, GIF（最大10MB）</p>
              <input type="file"
                multiple
                accept="image/*"
                class="hidden"
                @change="handleFileSelect($event)"
                x-ref="fileInput">
              <x-atoms.button variant="secondary" type="button" @click="$refs.fileInput.click()" class="mt-4">
                画像を選択
              </x-atoms.button>
            </div>

            <!-- プレビューグリッド -->
            <div x-show="images.length > 0" class="grid grid-cols-2 md:grid-cols-3 gap-4">
              <template x-for="(image, index) in images" :key="index">
                <div class="relative group aspect-square">
                  <img :src="image.url"
                    class="w-full h-full object-cover rounded-lg">
                  <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                    <button type="button"
                      @click="removeImage(index)"
                      class="opacity-0 group-hover:opacity-100 bg-red-500 text-white rounded-full p-2 transition-opacity duration-200">
                      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </template>

              <!-- 追加ボタン -->
              <button type="button"
                @click="$refs.fileInput.click()"
                class="aspect-square border-2 border-dashed border-neutral-300 rounded-lg hover:border-primary-400 transition-colors duration-200 flex items-center justify-center">
                <svg class="w-8 h-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </button>
            </div>
          </div>

          <div class="flex justify-end mt-6">
            <x-atoms.button variant="primary" type="button" @click="currentStep = 2" x-bind:disabled="images.length === 0">
              次へ
            </x-atoms.button>
          </div>
        </div>

        <!-- Step 2: 店舗選択 -->
        <div x-show="currentStep === 2" x-transition class="bg-white rounded-xl shadow-card p-6">
          <h2 class="text-xl font-bold text-neutral-900 mb-6">店舗を選択</h2>

          <!-- 最近の店舗 -->
          <div class="mb-6" x-show="recentShops.length > 0">
            <p class="text-sm text-neutral-600 mb-3">最近投稿した店舗</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <template x-for="shop in recentShops" :key="shop.id">
                <button type="button"
                  @click="selectShop(shop)"
                  :class="selectedShop && selectedShop.id === shop.id ? 'border-primary-500 bg-primary-50' : 'border-neutral-200'"
                  class="border-2 rounded-lg p-4 text-left hover:border-primary-300 transition-colors duration-200">
                  <p class="font-medium text-neutral-900" x-text="shop.name"></p>
                  <p class="text-sm text-neutral-600" x-text="shop.address"></p>
                </button>
              </template>
            </div>
          </div>

          <!-- 店舗検索 -->
          <div class="relative">
            <label class="block text-sm font-medium text-neutral-700 mb-2">店舗を検索</label>
            <div class="relative">
              <input type="text"
                x-model="shopSearchQuery"
                @input.debounce.300ms="searchShops()"
                placeholder="店舗名を入力（2文字以上）"
                class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <svg class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>

            <!-- 検索結果 -->
            <div x-show="showSearchResults"
              @click.outside="showSearchResults = false"
              class="absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-neutral-200 max-h-60 overflow-auto">
              <template x-for="shop in searchResults" :key="shop.id">
                <button type="button"
                  @click="selectShop(shop)"
                  class="w-full px-4 py-3 text-left hover:bg-neutral-50 border-b border-neutral-100 last:border-b-0">
                  <p class="font-medium text-neutral-900" x-text="shop.name"></p>
                  <p class="text-sm text-neutral-600" x-text="shop.address"></p>
                </button>
              </template>

              <!-- 新規作成オプション -->
              <button x-show="shopSearchQuery.length >= 2 && searchResults.length === 0"
                type="button"
                @click="showCreateShopModal = true"
                class="w-full px-4 py-3 text-left bg-primary-50 text-primary-600 hover:bg-primary-100">
                <p class="font-medium">「<span x-text="shopSearchQuery"></span>」を新規作成</p>
              </button>
            </div>
          </div>

          <!-- 選択された店舗の表示 -->
          <div x-show="selectedShop" class="mt-4 p-4 bg-primary-50 rounded-lg">
            <p class="text-sm text-primary-600 mb-1">選択中の店舗</p>
            <p class="font-medium text-neutral-900" x-text="selectedShop?.name"></p>
            <p class="text-sm text-neutral-600" x-text="selectedShop?.address"></p>
          </div>

          <input type="hidden" name="post[shop_id]" x-bind:value="selectedShop?.id || ''">

          <div class="flex justify-between mt-6">
            <x-atoms.button variant="ghost" type="button" @click="currentStep = 1">
              戻る
            </x-atoms.button>
            <x-atoms.button variant="primary" type="button" @click="currentStep = 3" x-bind:disabled="!selectedShop">
              次へ
            </x-atoms.button>
          </div>
        </div>

        <!-- Step 3: 詳細情報 -->
        <div x-show="currentStep === 3" x-transition class="bg-white rounded-xl shadow-card p-6">
          <h2 class="text-xl font-bold text-neutral-900 mb-6">詳細情報を入力</h2>

          <div class="space-y-6">
            <!-- 訪問ステータス -->
            <div>
              <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="post[visit_status]" value="1"
                  class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                <span class="text-neutral-700">訪問済み</span>
              </label>
            </div>

            <!-- 予算 -->
            <div>
              <label class="block text-sm font-medium text-neutral-700 mb-2">予算</label>
              <div x-data="{ budget: 3000 }">
                <input type="range"
                  x-model="budget"
                  name="post[budget]"
                  min="500"
                  max="50000"
                  step="500"
                  class="w-full h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer slider">
                <div class="flex justify-between items-center mt-2">
                  <span class="text-sm text-neutral-600">¥500</span>
                  <span class="text-2xl font-bold text-primary-500">¥<span x-text="budget.toLocaleString()"></span></span>
                  <span class="text-sm text-neutral-600">¥50,000</span>
                </div>
              </div>
            </div>

            <!-- 訪問日時 -->
            <div>
              <label for="visit_time" class="block text-sm font-medium text-neutral-700 mb-2">訪問日時</label>
              <input type="datetime-local"
                name="post[visit_time]"
                id="visit_time"
                class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- リピートメニュー -->
            <div>
              <label for="repeat_menu" class="block text-sm font-medium text-neutral-700 mb-2">
                リピートしたいメニュー
              </label>
              <textarea name="post[repeat_menu]"
                id="repeat_menu"
                rows="2"
                placeholder="また食べたいメニューを教えてください"
                class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
            </div>

            <!-- メモ -->
            <div>
              <label for="memo" class="block text-sm font-medium text-neutral-700 mb-2">
                メモ・感想
              </label>
              <textarea name="post[memo]"
                id="memo"
                rows="4"
                placeholder="お店の雰囲気や料理の感想など"
                class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
            </div>

            <!-- 公開設定 -->
            <div>
              <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="post[private_status]" value="1"
                  class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                <span class="text-neutral-700">非公開にする</span>
              </label>
            </div>
          </div>

          <div class="flex justify-between mt-6">
            <x-atoms.button variant="ghost" type="button" @click="currentStep = 2">
              戻る
            </x-atoms.button>
            <x-atoms.button variant="primary" type="button" @click="currentStep = 4">
              確認へ
            </x-atoms.button>
          </div>
        </div>

        <!-- Step 4: 確認 -->
        <div x-show="currentStep === 4" x-transition class="bg-white rounded-xl shadow-card p-6">
          <h2 class="text-xl font-bold text-neutral-900 mb-6">投稿内容の確認</h2>

          <div class="border border-neutral-200 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-neutral-900 mb-4">プレビュー</h3>

            <!-- ここに投稿のプレビューを表示 -->
            <div class="space-y-4">
              <!-- 画像プレビュー -->
              <div x-show="images.length > 0" class="grid grid-cols-3 gap-2">
                <template x-for="(image, index) in images.slice(0, 3)" :key="index">
                  <img :src="image.url" class="w-full h-24 object-cover rounded">
                </template>
              </div>

              <!-- 店舗情報 -->
              <div>
                <p class="font-medium text-neutral-900" x-text="selectedShop?.name"></p>
                <p class="text-sm text-neutral-600" x-text="selectedShop?.address"></p>
              </div>
            </div>
          </div>

          <div class="flex justify-between">
            <x-atoms.button variant="ghost" type="button" @click="currentStep = 3">
              戻る
            </x-atoms.button>
            <x-atoms.button variant="primary" type="submit">
              投稿する
            </x-atoms.button>
          </div>
        </div>

        <!-- 画像用のhiddenフィールド（動的に追加） -->
        <div x-ref="hiddenImages"></div>
      </form>
    </div>
  </div>
</div>

<!-- 新規店舗作成モーダル -->
<x-atoms.modal name="create-shop" :show="false">
  <div class="p-6">
    <h3 class="text-lg font-semibold text-neutral-900 mb-4">新しい店舗を追加</h3>
    <form x-data="createShopForm()" @submit.prevent="createNewShop">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-neutral-700 mb-1">店舗名</label>
          <input type="text" x-model="newShop.name" required
            class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-neutral-700 mb-1">住所</label>
          <input type="text" x-model="newShop.address" required
            class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500">
        </div>
      </div>
      <div class="flex justify-end space-x-3 mt-6">
        <x-atoms.button variant="ghost" type="button" @click="$dispatch('close-modal', 'create-shop')">
          キャンセル
        </x-atoms.button>
        <x-atoms.button variant="primary" type="submit">
          作成
        </x-atoms.button>
      </div>
    </form>
  </div>
</x-atoms.modal>

@verbatim
<script>
  function postForm() {
    return {
      currentStep: 1,
      images: [],
      selectedShop: null,
      shopSearchQuery: '',
      searchResults: [],
      showSearchResults: false,
      recentShops: [],
      showCreateShopModal: false,

      init() {
        // サーバーから渡された最近の店舗データを設定
        @if(isset($recentShops) && $recentShops -> isNotEmpty())
        this.recentShops = @json($recentShops);
        @endif

        // ドロップゾーンのイベントリスナー
        document.querySelectorAll('[data-drop-zone]').forEach(zone => {
          zone.addEventListener('drop', (e) => this.handleDrop(e));
          zone.addEventListener('dragover', (e) => e.preventDefault());
          zone.addEventListener('dragenter', (e) => e.preventDefault());
        });
      },

      handleDrop(e) {
        const files = Array.from(e.dataTransfer.files);
        this.processFiles(files);
      },

      handleFileSelect(e) {
        const files = Array.from(e.target.files);
        this.processFiles(files);
      },

      processFiles(files) {
        files.forEach(file => {
          if (file.type.startsWith('image/') && file.size <= 10 * 1024 * 1024) {
            const reader = new FileReader();
            reader.onload = (e) => {
              this.images.push({
                file: file,
                url: e.target.result,
                name: file.name
              });

              // hidden inputを作成してフォームに追加
              this.updateHiddenInputs();
            };
            reader.readAsDataURL(file);
          }
        });
      },

      removeImage(index) {
        this.images.splice(index, 1);
        this.updateHiddenInputs();
      },

      updateHiddenInputs() {
        const container = this.$refs.hiddenImages;
        container.innerHTML = '';

        this.images.forEach((image, index) => {
          const input = document.createElement('input');
          input.type = 'file';
          input.name = `post[images][${index}]`;
          input.style.display = 'none';

          // FileオブジェクトをDataTransferに変換してinputに設定
          const dt = new DataTransfer();
          dt.items.add(image.file);
          input.files = dt.files;

          container.appendChild(input);
        });
      },

      async searchShops() {
        if (this.shopSearchQuery.length < 2) {
          this.searchResults = [];
          this.showSearchResults = false;
          return;
        }

        try {
          const response = await fetch(`/shops/search?q=${encodeURIComponent(this.shopSearchQuery)}`);
          const data = await response.json();
          this.searchResults = data.shops || [];
          this.showSearchResults = true;
        } catch (error) {
          console.error('店舗検索エラー:', error);
          this.searchResults = [];
          this.showSearchResults = false;
        }
      },

      selectShop(shop) {
        this.selectedShop = shop;
        this.showSearchResults = false;
        this.shopSearchQuery = shop.name;
      }
    };
  }

  function createShopForm() {
    return {
      newShop: {
        name: '',
        address: ''
      },

      async createNewShop() {
        try {
          const response = await fetch('/shops', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(this.newShop)
          });

          const data = await response.json();

          if (data.success) {
            // 親コンポーネントの店舗を選択
            const postFormElement = document.querySelector('[x-data*="postForm"]');
            if (postFormElement && postFormElement._x_dataStack) {
              const postFormData = postFormElement._x_dataStack[0];
              postFormData.selectShop(data.shop);
            }

            // モーダルを閉じる
            this.$dispatch('close-modal', 'create-shop');

            // フォームをリセット
            this.newShop = {
              name: '',
              address: ''
            };
          } else {
            alert('店舗の作成に失敗しました');
          }
        } catch (error) {
          console.error('店舗作成エラー:', error);
          alert('店舗の作成中にエラーが発生しました');
        }
      }
    };
  }
</script>
@endverbatim

<style>
  /* スライダーのカスタムスタイル */
  .slider::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    background: #D64045;
    cursor: pointer;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: #D64045;
    cursor: pointer;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    border: none;
  }
</style>
@endsection