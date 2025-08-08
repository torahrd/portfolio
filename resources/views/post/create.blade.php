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

      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" x-data="shopSearchCsp">
        @csrf

        <!-- 店舗選択（CSP対応版） -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h3 class="text-lg font-bold mb-4">1. 店舗を選択 <span class="text-red-500">*</span></h3>
          <label for="shop_search" class="block text-sm font-medium text-gray-700 mb-2">店舗名を検索してください</label>
          <div class="relative">
            <input
              id="shop_search"
              type="text"
              placeholder="例：スターバックス 渋谷店"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              :value="searchQuery"
              @input="updateSearchQuery"
              @focus="showSearchResults"
              autocomplete="off">
            <div x-show="isLoading" class="absolute right-3 top-1/2 -translate-y-1/2">
              <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
            </div>
          </div>
          <!-- 検索結果表示エリア -->
          <div x-show="shouldShowResults" class="absolute left-0 right-0 z-40 w-full bg-white border border-gray-200 rounded-md mt-1 shadow-lg max-h-60 overflow-y-auto">
            <template x-for="(shop, index) in searchResults" :key="index">
              <div @click="selectShop" :data-shop-index="index" class="px-4 py-2 cursor-pointer hover:bg-blue-50 border-b border-gray-100 last:border-b-0">
                <div class="font-medium text-gray-900" x-text="shop.name"></div>
                <div class="text-sm text-gray-600" x-text="shop.address"></div>
              </div>
            </template>
          </div>
          <!-- 選択済み店舗表示 -->
          <template x-if="hasSelectedShop">
            <div class="mt-4 p-4 bg-blue-50 rounded-md">
              <div class="flex items-center justify-between">
                <div>
                  <div class="font-medium text-gray-900" x-text="getShopName"></div>
                  <div class="text-sm text-gray-600" x-text="getShopAddress"></div>
                  <div class="text-sm text-gray-600" x-show="selectedShop" x-text="getPhoneNumberText"></div>
                </div>
                <button type="button" @click="clearSelection" class="text-red-500 hover:text-red-700 text-sm">変更</button>
              </div>
              <input type="hidden" name="post[shop_id]" :value="getShopId">
              <input type="hidden" name="post[google_place_id]" :value="getGooglePlaceId">
              <input type="hidden" name="post[shop_name]" :value="getShopNameForForm">
              <input type="hidden" name="post[shop_address]" :value="getShopAddressForForm">
            </div>
          </template>
          <!-- バリデーションUI -->
          <template x-if="hasSelectedShop">
            <div x-show="isSelectionValid" class="mt-2 p-2 bg-green-50 border border-green-200 rounded flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <span class="text-green-700 text-sm">店舗選択が有効です</span>
            </div>
          </template>
          <template x-if="hasSelectedShop">
            <div x-show="isSelectionInvalid" class="mt-2 p-2 bg-red-50 border border-red-200 rounded flex items-center gap-2">
              <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <span class="text-red-700 text-sm">店舗選択が無効です</span>
            </div>
          </template>
          <div x-show="errorMessage" class="mt-2 text-sm text-red-600" x-text="errorMessage"></div>
          <p class="text-sm text-gray-600 mt-2">
            店舗名を入力すると、Google Places APIからリアルタイムで検索結果が表示されます。
            Google Places APIが利用できない場合は、既存のデータベースから検索結果を表示します。
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

        <button
          type="submit"
          :disabled="!selectedShop"
          :class="getSimpleSubmitButtonClass"
          class="px-6 py-3 text-white rounded-md transition-colors">
          投稿する
        </button>
      </form>
    </div>
  </div>
@endsection