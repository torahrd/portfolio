@props([
'placeholder' => '店舗やグルメを検索...',
'value' => '',
'suggestions' => [],
'action' => '/search'
])

<div class="relative"
  x-data="searchBar"
  data-initial-value="{{ $value }}"
  data-suggestions="{{ json_encode($suggestions) }}"
  @click.outside="handleClickOutside">
  <form action="{{ $action }}" method="GET" class="relative">
    <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>

      <input
        type="text"
        name="q"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        class="block w-full pl-12 pr-12 py-3 border border-neutral-300 rounded-xl bg-white text-neutral-900 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
        :value="query"
        @input="updateQuery"
        @focus="showSuggestionsOnFocus"
        autocomplete="off" />

      <!-- ローディング表示 -->
      <div x-show="isLoading" class="absolute inset-y-0 right-0 pr-12 flex items-center">
        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary-500"></div>
      </div>

      <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
        <button
          type="submit"
          class="p-1 rounded-lg text-neutral-400 hover:text-primary-500 focus:outline-none focus:text-primary-500 transition-colors duration-200">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
          </svg>
        </button>
      </div>
    </div>
  </form>

  <!-- 検索候補 -->
  <div x-show="shouldShowSuggestions"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-neutral-200 py-2 z-40 max-h-80 overflow-y-auto">

    <!-- ローディング中 -->
    <div x-show="isLoading" class="px-4 py-3 text-center text-neutral-500">
      <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-primary-500 mx-auto mb-2"></div>
      <div class="text-sm">検索中...</div>
    </div>

    <!-- 検索結果 -->
    <template x-for="(suggestion, index) in suggestions" :key="suggestion.title">
      <div @click="selectSuggestion" :data-suggestion-index="index"
        class="px-4 py-3 cursor-pointer hover:bg-neutral-50 transition-colors duration-200">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <div class="font-medium text-neutral-900" x-text="suggestion.title"></div>
            <div class="text-sm text-neutral-600" x-text="suggestion.subtitle"></div>
          </div>
          <div class="flex items-center space-x-2">
            <!-- 投稿数表示 -->
            <div x-show="hasPosts" class="text-xs bg-primary-100 text-primary-700 px-2 py-1 rounded-full">
              <span x-text="suggestion.post_count"></span>件の投稿
            </div>
            <div x-show="hasNoPosts" class="text-xs bg-neutral-100 text-neutral-600 px-2 py-1 rounded-full">
              投稿なし
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- 結果なし -->
    <div x-show="shouldShowNoResults"
      class="px-4 py-3 text-center text-neutral-500">
      <div class="text-sm">該当する店舗が見つかりませんでした</div>
    </div>
  </div>
</div> 