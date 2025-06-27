<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">新規投稿作成</h2>

    <div class="max-w-3xl mx-auto">
      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- 店舗選択（必須） -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h3 class="text-lg font-bold mb-4">1. 店舗を選択 <span class="text-red-500">*</span></h3>

          <!-- Google Places API連携の店舗検索 -->
          <div class="mb-4" x-data="shopSearch()">
            <label for="shop_search" class="block text-sm font-medium text-gray-700 mb-2">店舗名を検索してください</label>

            <!-- 検索入力フィールド -->
            <div class="relative">
              <input
                id="shop_search"
                type="text"
                placeholder="例：スターバックス 渋谷店"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                x-model="searchQuery"
                @input="searchShops()"
                @focus="showResults = true"
                autocomplete="off"
                required>

              <!-- 検索中インジケーター -->
              <div x-show="isLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
              </div>
            </div>

            <!-- 検索結果表示エリア -->
            <div x-show="showResults && searchResults.length > 0"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 transform scale-95"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-95"
              class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">

              <!-- 検索結果件数表示 -->
              <div class="px-3 py-2 bg-gray-50 border-b border-gray-200 text-xs text-gray-600">
                <span x-text="searchResults.length"></span>件の検索結果
              </div>

              <template x-for="(shop, index) in searchResults" :key="index">
                <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                  @click="selectShop(shop)">
                  <div class="flex items-center justify-between">
                    <div class="flex-1">
                      <div class="flex items-center space-x-2">
                        <div class="font-medium text-gray-900" x-text="shop.name"></div>
                        <!-- マッチスコアに基づくバッジ -->
                        <span x-show="shop.match_score >= 100"
                          class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                          完全一致
                        </span>
                        <span x-show="shop.match_score >= 80 && shop.match_score < 100"
                          class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                          前方一致
                        </span>
                        <span x-show="shop.match_score >= 60 && shop.match_score < 80"
                          class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                          部分一致
                        </span>
                      </div>
                      <div class="text-sm text-gray-500 mt-1" x-text="shop.address"></div>
                      <div class="text-xs text-gray-400 mt-1" x-show="shop.formatted_phone_number" x-text="shop.formatted_phone_number"></div>
                    </div>
                    <div class="flex items-center space-x-2 ml-3">
                      <!-- 既存店舗バッジ -->
                      <span x-show="shop.is_existing"
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        既存
                      </span>
                      <!-- 新規店舗バッジ -->
                      <span x-show="!shop.is_existing"
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        新規
                      </span>
                    </div>
                  </div>
                </div>
              </template>

              <!-- 検索結果が5件に達した場合のメッセージ -->
              <div x-show="searchResults.length >= 5" class="px-3 py-2 bg-gray-50 text-xs text-gray-500 text-center">
                上位5件を表示しています
              </div>
            </div>

            <!-- 選択された店舗情報表示 -->
            <div x-show="selectedShop" class="mt-4 p-4 bg-blue-50 rounded-md">
              <div class="flex items-center justify-between">
                <div>
                  <div class="font-medium text-gray-900" x-text="selectedShop ? selectedShop.name : ''"></div>
                  <div class="text-sm text-gray-600" x-text="selectedShop ? selectedShop.address : ''"></div>
                  <div class="text-sm text-gray-600" x-show="selectedShop && selectedShop.formatted_phone_number" x-text="selectedShop ? selectedShop.formatted_phone_number : ''"></div>
                </div>
                <button type="button"
                  @click="clearSelection()"
                  class="text-red-500 hover:text-red-700 text-sm">
                  変更
                </button>
              </div>

              <!-- 隠しフィールドで店舗IDを送信 -->
              <input type="hidden" name="post[shop_id]" x-bind:value="selectedShop ? selectedShop.id : ''">
              <input type="hidden" name="post[google_place_id]" x-bind:value="selectedShop ? selectedShop.google_place_id : ''">
            </div>

            <!-- エラーメッセージ -->
            <div x-show="errorMessage" class="mt-2 text-sm text-red-600" x-text="errorMessage"></div>
          </div>

          <p class="text-sm text-gray-600">
            店舗名を入力すると、Google Places APIからリアルタイムで検索結果が表示されます。
          </p>
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
</x-app-layout>