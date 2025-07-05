@props([
'name' => 'shop_id',
'initialShop' => null,
'label' => '店舗名を検索してください',
'mode' => 'post', // 'post' or 'search' など
])

<div x-data="shopSearch({ initialShop: @js($initialShop), mode: '{{ $mode }}' })" class="relative">
  <label for="shop_search" class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
  <div class="relative">
    <input
      id="shop_search"
      type="text"
      placeholder="例：スターバックス 渋谷店"
      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
      x-model="searchQuery"
      @input="searchShops()"
      @focus="showResults = true"
      autocomplete="off">
    <div x-show="isLoading" class="absolute right-3 top-1/2 -translate-y-1/2">
      <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
    </div>
  </div>
  <!-- 検索結果表示エリア -->
  <div x-show="showResults && searchResults.length > 0" class="absolute left-0 right-0 z-40 w-full bg-white border border-gray-200 rounded-md mt-1 shadow-lg">
    <template x-for="(shop, index) in searchResults" :key="shop.id ? shop.id : index">
      <div @click="selectShop(shop)" class="px-4 py-2 cursor-pointer hover:bg-blue-50">
        <div class="font-medium text-gray-900" x-text="shop.name"></div>
        <div class="text-sm text-gray-600" x-text="shop.address"></div>
      </div>
    </template>
  </div>
  <!-- 選択済み店舗表示（投稿用UIのみ） -->
  <template x-if="selectedShop && mode === 'post'">
    <div class="mt-4 p-4 bg-blue-50 rounded-md">
      <div class="flex items-center justify-between">
        <div>
          <div class="font-medium text-gray-900" x-text="selectedShop && selectedShop.name ? selectedShop.name : ''"></div>
          <div class="text-sm text-gray-600" x-text="selectedShop && selectedShop.address ? selectedShop.address : ''"></div>
          <div class="text-sm text-gray-600" x-show="selectedShop && selectedShop.formatted_phone_number" x-text="selectedShop && selectedShop.formatted_phone_number ? selectedShop.formatted_phone_number : ''"></div>
        </div>
        <button type="button" @click="clearSelection()" class="text-red-500 hover:text-red-700 text-sm">変更</button>
      </div>
      <input type="hidden" :name="'{{ $name }}'" x-bind:value="selectedShop && selectedShop.id ? selectedShop.id : ''">
      <input type="hidden" name="post[google_place_id]" x-bind:value="selectedShop && selectedShop.google_place_id ? selectedShop.google_place_id : ''">
      <input type="hidden" name="post[shop_name]" x-bind:value="selectedShop && selectedShop.name ? selectedShop.name : ''">
      <input type="hidden" name="post[shop_address]" x-bind:value="selectedShop && selectedShop.address ? selectedShop.address : ''">
    </div>
  </template>
  <!-- バリデーションUI（選択済みかつバリデーションOK時のみ） -->
  <div x-show="selectedShop && isSelectionValid" class="mt-2 p-2 bg-green-50 border border-green-200 rounded flex items-center gap-2">
    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    <span class="text-green-700 text-sm">店舗選択が有効です</span>
  </div>
  <div x-show="selectedShop && !isSelectionValid" class="mt-2 p-2 bg-red-50 border border-red-200 rounded flex items-center gap-2">
    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>
    <span class="text-red-700 text-sm">店舗選択が無効です</span>
  </div>
  <div x-show="errorMessage" class="mt-2 text-sm text-red-600" x-text="errorMessage"></div>
  <p class="text-sm text-gray-600 mt-2">
    店舗名を入力すると、Google Places APIからリアルタイムで検索結果が表示されます。
    Google Places APIが利用できない場合は、既存のデータベースから検索結果を表示します。
  </p>
</div>